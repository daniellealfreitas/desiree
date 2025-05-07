/**
 * Auto Geolocation Script
 *
 * Este script gerencia a detecção automática de localização do usuário
 * e atualiza as coordenadas no servidor quando necessário.
 */

document.addEventListener('DOMContentLoaded', function () {
    // Verifica se o usuário está autenticado (verificamos pela presença do token CSRF)
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) return;

    // Configurações
    const CONFIG = {
        // Intervalo para verificar a localização (em milissegundos)
        // 1 hora = 3600000 ms
        CHECK_INTERVAL: 3600000,

        // Distância mínima (em metros) para considerar uma mudança de localização
        MIN_DISTANCE: 100,

        // Tempo máximo (em milissegundos) para considerar coordenadas válidas
        // 24 horas = 86400000 ms
        MAX_COORDS_AGE: 86400000,

        // Opções para a API de geolocalização
        GEO_OPTIONS: {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        }
    };

    // Verifica se o navegador suporta geolocalização
    if (!navigator.geolocation) {
        console.log('Geolocalização não é suportada por este navegador');
        return;
    }

    // Verifica se o navegador suporta localStorage
    if (!window.localStorage) {
        console.log('LocalStorage não é suportado por este navegador');
        return;
    }

    // Função para calcular a distância entre duas coordenadas (fórmula de Haversine)
    function calculateDistance(lat1, lon1, lat2, lon2) {
        try {
            // Garantir que todos os valores são numéricos
            lat1 = parseFloat(lat1);
            lon1 = parseFloat(lon1);
            lat2 = parseFloat(lat2);
            lon2 = parseFloat(lon2);

            // Verificar se algum valor é inválido
            if (isNaN(lat1) || isNaN(lon1) || isNaN(lat2) || isNaN(lon2)) {
                console.error('Valores inválidos para cálculo de distância:', { lat1, lon1, lat2, lon2 });
                return 999999; // Retorna um valor grande para indicar distância inválida
            }

            const R = 6371e3; // Raio da Terra em metros
            const φ1 = lat1 * Math.PI / 180;
            const φ2 = lat2 * Math.PI / 180;
            const Δφ = (lat2 - lat1) * Math.PI / 180;
            const Δλ = (lon2 - lon1) * Math.PI / 180;

            const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
                Math.cos(φ1) * Math.cos(φ2) *
                Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

            return R * c; // Distância em metros
        } catch (e) {
            console.error('Erro ao calcular distância:', e);
            return 999999; // Retorna um valor grande para indicar distância inválida
        }
    }

    // Função para verificar se devemos atualizar a localização
    function shouldUpdateLocation(newLat, newLng) {
        try {
            // Garantir que os valores são numéricos
            const lat = parseFloat(newLat);
            const lng = parseFloat(newLng);

            // Validar se os valores são válidos
            if (isNaN(lat) || isNaN(lng)) {
                console.error('Coordenadas inválidas para verificação:', newLat, newLng);
                return false;
            }

            // Obtém as coordenadas salvas anteriormente
            const savedCoords = JSON.parse(localStorage.getItem('userCoords') || '{}');

            // Se não houver coordenadas salvas, devemos atualizar
            if (!savedCoords.latitude || !savedCoords.longitude) {
                return true;
            }

            // Garantir que os valores salvos são numéricos
            const savedLat = parseFloat(savedCoords.latitude);
            const savedLng = parseFloat(savedCoords.longitude);

            // Validar se os valores salvos são válidos
            if (isNaN(savedLat) || isNaN(savedLng)) {
                console.error('Coordenadas salvas inválidas:', savedCoords);
                return true; // Atualiza se as coordenadas salvas forem inválidas
            }

            // Verifica se as coordenadas salvas são muito antigas
            if (savedCoords.timestamp && (Date.now() - savedCoords.timestamp > CONFIG.MAX_COORDS_AGE)) {
                return true;
            }

            // Calcula a distância entre as coordenadas antigas e novas
            const distance = calculateDistance(
                savedLat,
                savedLng,
                lat,
                lng
            );

            // Atualiza se a distância for maior que o mínimo configurado
            return distance > CONFIG.MIN_DISTANCE;
        } catch (e) {
            console.error('Erro ao verificar atualização de localização:', e);
            return false;
        }
    }

    // Função para atualizar a localização no servidor
    function updateLocationOnServer(latitude, longitude) {
        // Garantir que os valores são numéricos
        const lat = parseFloat(latitude);
        const lng = parseFloat(longitude);

        // Validar se os valores são válidos
        if (isNaN(lat) || isNaN(lng)) {
            console.error('Coordenadas inválidas:', latitude, longitude);
            return;
        }

        // Validar se os valores estão dentro de limites razoáveis
        if (lat < -90 || lat > 90 || lng < -180 || lng > 180) {
            console.error('Coordenadas fora dos limites válidos:', lat, lng);
            return;
        }

        fetch('/update-user-location', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                latitude: lat,
                longitude: lng
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Localização atualizada com sucesso');

                    // Salva as novas coordenadas no localStorage
                    localStorage.setItem('userCoords', JSON.stringify({
                        latitude: lat,
                        longitude: lng,
                        timestamp: Date.now()
                    }));
                } else {
                    console.error('Erro ao atualizar localização:', data.message);
                }
            })
            .catch(error => {
                console.error('Erro ao enviar coordenadas:', error);
            });
    }

    // Função principal para obter e atualizar a localização
    function checkAndUpdateLocation() {
        navigator.geolocation.getCurrentPosition(
            // Sucesso
            function (position) {
                try {
                    // Garantir que os valores são numéricos
                    const latitude = parseFloat(position.coords.latitude);
                    const longitude = parseFloat(position.coords.longitude);

                    // Validar se os valores são válidos
                    if (isNaN(latitude) || isNaN(longitude)) {
                        console.error('Coordenadas inválidas recebidas do navegador:', position.coords);
                        return;
                    }

                    // Validar se os valores estão dentro de limites razoáveis
                    if (latitude < -90 || latitude > 90 || longitude < -180 || longitude > 180) {
                        console.error('Coordenadas fora dos limites válidos:', latitude, longitude);
                        return;
                    }

                    // Verifica se devemos atualizar a localização
                    if (shouldUpdateLocation(latitude, longitude)) {
                        updateLocationOnServer(latitude, longitude);
                    }
                } catch (e) {
                    console.error('Erro ao processar coordenadas:', e);
                }
            },
            // Erro
            function (error) {
                let errorMessage = 'Erro ao obter localização';

                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage = 'Permissão de localização negada';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage = 'Informação de localização indisponível';
                        break;
                    case error.TIMEOUT:
                        errorMessage = 'Tempo esgotado ao obter localização';
                        break;
                }

                console.log(errorMessage);
            },
            // Opções
            CONFIG.GEO_OPTIONS
        );
    }

    // Verifica a localização imediatamente ao carregar a página
    checkAndUpdateLocation();

    // Configura verificação periódica da localização
    setInterval(checkAndUpdateLocation, CONFIG.CHECK_INTERVAL);
});
