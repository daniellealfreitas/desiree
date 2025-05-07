<div>
    <!-- Componente de animações -->
    <script>
        // Função para disparar a animação de confetti
        window.triggerConfetti = function() {
            // Cria um elemento canvas dinamicamente
            const canvas = document.createElement('canvas');
            document.body.appendChild(canvas);
            canvas.style.position = 'fixed';
            canvas.style.top = '0';
            canvas.style.left = '0';
            canvas.style.width = '100%';
            canvas.style.height = '100%';
            canvas.style.pointerEvents = 'none';
            canvas.style.zIndex = '9999';
            const ctx = canvas.getContext('2d');
            const confettiCount = 300;
            const confetti = [];

            // Inicializa as partículas de confetti
            for (let i = 0; i < confettiCount; i++) {
                confetti.push({
                    x: Math.random() * canvas.width,
                    y: Math.random() * canvas.height - canvas.height,
                    r: Math.random() * 6 + 2,
                    dx: Math.random() * 4 - 2,
                    dy: Math.random() * 4 + 2,
                    color: `hsl(${Math.random() * 360}, 100%, 50%)`
                });
            }

            // Redimensiona o canvas para corresponder ao tamanho da janela
            function resizeCanvas() {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
            }
            resizeCanvas();
            window.addEventListener('resize', resizeCanvas);

            // Loop de animação
            function animate() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                confetti.forEach(p => {
                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                    ctx.fillStyle = p.color;
                    ctx.fill();
                    p.x += p.dx;
                    p.y += p.dy;
                    if (p.y > canvas.height) p.y = -p.r;
                });
                requestAnimationFrame(animate);
            }
            animate();

            // Remove o canvas após 5 segundos
            setTimeout(() => {
                window.removeEventListener('resize', resizeCanvas);
                canvas.remove();
            }, 5000);
        };

        // Função para disparar o popup de XP
        window.triggerXpPopup = function(points) {
            // Cria um elemento popup dinamicamente
            const popup = document.createElement('div');
            popup.textContent = `+${points} XP!`;
            popup.className = 'fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-white text-4xl font-bold bg-blue-500 px-6 py-3 rounded-lg shadow-lg z-[9999]';
            popup.style.animation = 'xpPopup 2s ease-out forwards';
            document.body.appendChild(popup);

            // Adiciona a animação CSS
            const style = document.createElement('style');
            style.textContent = `
                @keyframes xpPopup {
                    0% { opacity: 0; transform: translate(-50%, -50%) scale(0.5); }
                    20% { opacity: 1; transform: translate(-50%, -50%) scale(1.2); }
                    30% { transform: translate(-50%, -50%) scale(1); }
                    70% { opacity: 1; transform: translate(-50%, -50%) scale(1); }
                    100% { opacity: 0; transform: translate(-50%, -50%) scale(1) translateY(-50px); }
                }
            `;
            document.head.appendChild(style);

            // Remove o popup após 2 segundos
            setTimeout(() => {
                popup.remove();
                style.remove();
            }, 2000);
        };

        // Função para disparar ambas as animações
        window.triggerRewardAnimations = function(points) {
            window.triggerConfetti();
            window.triggerXpPopup(points);
        };

        // Adiciona atalho de teclado para testar as animações (F10)
        document.addEventListener('keydown', function(event) {
            if (event.key === 'F10') {
                window.triggerRewardAnimations(50);
            }
        });
    </script>
</div>
