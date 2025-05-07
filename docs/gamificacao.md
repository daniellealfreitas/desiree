# Sistema de Gamificação para Rede Social - Projeto NOVAZ

## Sumário

1. [Objetivo Geral](#objetivo-geral)
2. [Resumo dos Componentes do Sistema](#resumo-dos-componentes-do-sistema)
3. [Roadmap de Implementação](#roadmap-de-implementação)
4. [Descrição Técnica de Cada Etapa](#descrição-técnica-de-cada-etapa)
5. [Considerações Finais & Sugestões Futuras](#considerações-finais--sugestões-futuras)

---

## Objetivo Geral

Aumentar engajamento, promover competição saudável e recompensa, estimular criação/curadoria de conteúdo e utilizar reforço positivo por técnicas de gamificação dentro da rede social.

---

## Resumo dos Componentes do Sistema

- **Pontuação por ações do usuário** (postar, comentar, curtir, etc.)
- **Sistema de níveis (levels)**, faixas, XP e badges
- **Ranking semanal e mensal**, com reset, histórico, destaques e premiações
- **Conquistas (achievements)**
- **Loja virtual e moedas internas**
- **Missões/desafios diários e semanais**
- **Gatilhos visuais e sonoros** (feedback instantâneo)
- **Notificações e progresso visível**

---

## Roadmap de Implementação

### Fase 1: Estrutura de Dados & Models

1. [ ] Criar migrations/tabelas:
    - user_scores
    - user_levels
    - user_achievements
    - badges
    - missions
    - store_items
    - purchase_history

2. [ ] Adicionar campos necessários em cada tabela:
    - Pontuação acumulada, semanal, mensal
    - Níveis, badges, moedas, conquistas, histórico de ranking, etc.

3. [ ] Criar modelos Laravel correspondentes e relacionamentos para User

---

### Fase 2: Lógica de Pontuação & Acompanhamento

4. [ ] Implementar eventos/listeners para:
    - Postagem, comentário, curtida (dada/recebida), logins, compartilhamentos, denúncias válidas, postar imagem/vídeo

5. [ ] Implementar aplicação automática de multiplicador (horário de pico)

6. [ ] Funções para cálculo de score total/final e engajamento médio

7. [ ] Função para atualização semanal/mensal dos scores/rankings com reset e salvando histórico

---

### Fase 3: Sistema de Levels, Ranking, Progressão e Premiação

8. [ ] Mapear faixas de nível & lógica de progressão

9. [ ] Algoritmo de cálculo de ranking:
    - Top 10 semanal/mensal
    - Destacar, atribuir badges e sons para conquistas

10. [ ] Testes unitários para todas as regras de negócio

---

### Fase 4: Missões, Conquistas, Loja

11. [ ] Criar lógica para missões (diárias/semanais), conquistas (achievements) e distribuição de recompensas

12. [ ] Implementar store: sistema de moedas, resgate de prêmios, badges especiais

13. [ ] Regra especial: compra de ingresso para evento (Desiree) concede pontos e badges exclusivos

---

### Fase 5: Integração Frontend & Feedback

14. [ ] Barra de XP animada, painel de progresso e badges visíveis no perfil

15. [ ] Páginas públicas de ranking com medalhas, destaque top 10

16. [ ] Animações e sons (subida de nível, conquista, entrada top 10, etc.)

17. [ ] Pop-ups motivacionais e notificações sobre progresso/performance

18. [ ] Exposição do histórico de ranking, badges, conquistas e moedas

---

## Descrição Técnica de Cada Etapa

### 1. Migrations e Models

- Cada módulo deve ter sua migration e model dedicado.
- Relacionar User com Score, Levels, Badges, Achievements, Missions, Store e Purchase.

### 2. Eventos de Ação

- Utilizar eventos nativos do Laravel relacionados a criação de posts, comentários etc.
- Implementar listeners para atualizar score e acionar recompensas.

### 3. Ranking & Reset

- Função agendada via task/artisan command para computar/resetar ranking semanal/mensal e salvar histórico.

### 4. Missões, Conquistas & Store

- Missões devem ser flexíveis e cadastradas via banco (tipo, meta e recompensa).
- Achievements disparados por milestones acumulativos.
- Compra na loja desconta moedas e atribui item/badge ao usuário.

### 5. Frontend, Feedback Visual/Sonoro

- Utilizar Livewire e Blade para interações dinâmicas (barra XP, animações).
- Loader de sons e animações integrado a eventos do sistema.
- Pop-ups via JS/Livewire para notificações motivacionais.

---

## Considerações Finais & Sugestões Futuras

- Integrar analytics para acompanhar o impacto da gamificação.
- Possibilidade de API externa para consultar ranking/performance.
- Sistema modular para fácil adição de novos tipos de missões, conquistas e recompensas.
- Avaliar uso de websocket para updates em tempo real de ranking e badges.

---

**Este documento deve ser mantido atualizado a cada sprint para refletir o andamento do projeto e facilitar a integração de novos desenvolvedores.**