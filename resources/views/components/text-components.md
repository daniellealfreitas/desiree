# Componentes de Texto Padronizados

Este documento descreve os componentes de texto padronizados disponíveis no projeto. Estes componentes foram criados para garantir consistência visual em toda a aplicação, especialmente entre os temas claro e escuro.

## Classes de Texto Padronizadas

As classes de texto padronizadas estão definidas em `resources/css/text-colors.css` e podem ser usadas diretamente em elementos HTML ou através dos componentes descritos abaixo.

### Exemplos de Classes

```html
<h1 class="text-title">Título Principal</h1>
<p class="text-body">Texto do corpo</p>
<a href="#" class="text-link">Link</a>
```

## Componentes Disponíveis

### 1. Componente `<x-text>`

Um componente versátil para exibir texto com diferentes estilos.

#### Propriedades

- `variant`: Define o estilo do texto (padrão: 'body')
  - Valores: 'title', 'subtitle', 'body', 'body-light', 'body-lighter', 'link', 'link-subtle', 'label', 'accent', 'success', 'warning', 'danger', 'info', 'disabled', 'price', 'price-discount', 'price-old'
- `as`: Define o elemento HTML a ser renderizado (padrão: 'p')
- `size`: Define o tamanho do texto
  - Valores: 'xs', 'sm', 'base', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl'
- `weight`: Define o peso da fonte
  - Valores: 'thin', 'extralight', 'light', 'normal', 'medium', 'semibold', 'bold', 'extrabold', 'black'
- `color`: Define uma cor personalizada (sobrescreve a variante)

#### Exemplos

```blade
<x-text>Texto padrão</x-text>
<x-text variant="title" size="2xl" weight="bold">Título Grande</x-text>
<x-text variant="body-light" as="span">Texto leve</x-text>
<x-text variant="success" weight="semibold">Mensagem de sucesso</x-text>
```

### 2. Componente `<x-heading>`

Um componente para cabeçalhos com níveis de 1 a 6.

#### Propriedades

- `level`: Define o nível do cabeçalho (1-6, padrão: 2)
- `size`: Define o tamanho do texto (sobrescreve o tamanho padrão baseado no nível)
- `weight`: Define o peso da fonte (padrão: 'semibold')
- `color`: Define uma cor personalizada (padrão: 'text-title')

#### Exemplos

```blade
<x-heading level="1">Título Principal</x-heading>
<x-heading level="2" size="4xl">Título Secundário Grande</x-heading>
<x-heading level="3" weight="bold" color="purple-600">Título Terciário Colorido</x-heading>
```

### 3. Componente `<x-paragraph>`

Um componente específico para parágrafos.

#### Propriedades

- `variant`: Define o estilo do texto (padrão: 'body')
- `size`: Define o tamanho do texto (padrão: 'base')
- `weight`: Define o peso da fonte (padrão: 'normal')
- `color`: Define uma cor personalizada
- `leading`: Define a altura da linha (padrão: 'normal')
  - Valores: 'none', 'tight', 'snug', 'normal', 'relaxed', 'loose'

#### Exemplos

```blade
<x-paragraph>Parágrafo padrão</x-paragraph>
<x-paragraph variant="body-light" size="lg" leading="relaxed">
    Parágrafo com texto maior e mais espaçado
</x-paragraph>
<x-paragraph variant="warning" weight="medium">Aviso importante</x-paragraph>
```

### 4. Componente `<x-link>`

Um componente para links.

#### Propriedades

- `variant`: Define o estilo do link (padrão: 'primary')
  - Valores: 'primary', 'subtle', 'accent', 'success', 'warning', 'danger', 'info', 'disabled'
- `size`: Define o tamanho do texto (padrão: 'base')
- `weight`: Define o peso da fonte (padrão: 'medium')
- `color`: Define uma cor personalizada
- `href`: Define o URL do link (padrão: '#')
- `external`: Define se o link é externo (padrão: false)
- `underline`: Define se o link deve ter sublinhado ao passar o mouse (padrão: true)

#### Exemplos

```blade
<x-link href="/pagina">Link interno</x-link>
<x-link href="https://exemplo.com" external="true">Link externo</x-link>
<x-link variant="subtle" underline="false">Link sutil sem sublinhado</x-link>
<x-link variant="danger" size="lg" weight="bold">Link de ação importante</x-link>
```

## Uso com Temas Claro/Escuro

Todos os componentes e classes são compatíveis com os temas claro e escuro. As cores são automaticamente ajustadas com base no tema ativo.
