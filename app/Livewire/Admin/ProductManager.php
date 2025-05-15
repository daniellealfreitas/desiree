<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ProductManager extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $sortBy = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $categoryFilter = '';

    // Campos do formulário
    public $productId;
    public $name;
    public $description;
    public $price;
    public $salePrice;
    public $stock;
    public $categoryId;
    public $featured = false;
    public $status = 'active';
    public $sku;
    public $weight;
    public $color;
    public $saleStartsAt;
    public $saleEndsAt;
    public $image;
    public $additionalImages = [];

    // Campos para produtos digitais
    public $isDigital = false;
    public $digitalFile;
    public $digitalFileName;
    public $downloadLimit;
    public $downloadExpiryDays;

    // Controle de modal
    public $showModal = false;
    public $confirmingDelete = false;
    public $deleteId;
    public $isEditing = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'salePrice' => 'nullable|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'categoryId' => 'nullable|exists:categories,id',
        'featured' => 'boolean',
        'status' => 'required|in:active,inactive',
        'sku' => 'nullable|string|max:100',
        'weight' => 'nullable|numeric|min:0',
        'color' => 'nullable|string|max:50',
        'saleStartsAt' => 'nullable|date',
        'saleEndsAt' => 'nullable|date|after_or_equal:saleStartsAt',
        'image' => 'nullable|image|max:2048',
        'additionalImages.*' => 'nullable|image|max:2048',
        'isDigital' => 'boolean',
        'digitalFile' => 'nullable|file|max:51200|mimes:pdf,zip,doc,docx,xls,xlsx,ppt,pptx,mp3,mp4,jpg,jpeg,png,gif',
        'digitalFileName' => 'nullable|string|max:255',
        'downloadLimit' => 'nullable|integer|min:0',
        'downloadExpiryDays' => 'nullable|integer|min:0',
    ];

    protected $messages = [
        'name.required' => 'O nome do produto é obrigatório.',
        'name.max' => 'O nome do produto não pode ter mais de 255 caracteres.',
        'price.required' => 'O preço do produto é obrigatório.',
        'price.numeric' => 'O preço deve ser um valor numérico.',
        'price.min' => 'O preço não pode ser negativo.',
        'salePrice.numeric' => 'O preço promocional deve ser um valor numérico.',
        'salePrice.min' => 'O preço promocional não pode ser negativo.',
        'stock.required' => 'O estoque é obrigatório.',
        'stock.integer' => 'O estoque deve ser um número inteiro.',
        'stock.min' => 'O estoque não pode ser negativo.',
        'categoryId.exists' => 'A categoria selecionada não existe.',
        'status.required' => 'O status do produto é obrigatório.',
        'status.in' => 'O status deve ser ativo ou inativo.',
        'weight.numeric' => 'O peso deve ser um valor numérico.',
        'weight.min' => 'O peso não pode ser negativo.',
        'saleEndsAt.after_or_equal' => 'A data de término da promoção deve ser posterior à data de início.',
        'image.image' => 'O arquivo deve ser uma imagem.',
        'image.max' => 'A imagem não pode ter mais de 2MB.',
        'additionalImages.*.image' => 'Todos os arquivos adicionais devem ser imagens.',
        'additionalImages.*.max' => 'As imagens adicionais não podem ter mais de 2MB.',
        'digitalFile.file' => 'O arquivo digital deve ser um arquivo válido.',
        'digitalFile.max' => 'O arquivo digital não pode ter mais de 50MB.',
        'digitalFile.mimes' => 'O arquivo digital deve ser um dos seguintes formatos: PDF, ZIP, DOC, DOCX, XLS, XLSX, PPT, PPTX, MP3, MP4, JPG, JPEG, PNG, GIF.',
        'downloadLimit.integer' => 'O limite de downloads deve ser um número inteiro.',
        'downloadLimit.min' => 'O limite de downloads não pode ser negativo.',
        'downloadExpiryDays.integer' => 'O prazo de expiração deve ser um número inteiro de dias.',
        'downloadExpiryDays.min' => 'O prazo de expiração não pode ser negativo.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatedIsDigital()
    {
        // Limpar campos de produto digital quando o usuário desmarcar a opção
        if (!$this->isDigital) {
            $this->digitalFile = null;
            $this->digitalFileName = '';
            $this->downloadLimit = null;
            $this->downloadExpiryDays = null;
        }
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function create()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetForm();
        $this->isEditing = true;
        $this->productId = $id;

        try {
            $product = Product::with('images')->findOrFail($id);

            $this->name = $product->name;
            $this->description = $product->description;

            // Valores numéricos
            $this->price = $product->price ?? 0;
            $this->salePrice = $product->sale_price;
            $this->weight = $product->weight;
            $this->stock = $product->stock;

            // Outros campos
            $this->categoryId = $product->category_id;
            $this->featured = (bool)$product->featured;
            $this->status = $product->status;
            $this->sku = $product->sku;
            $this->color = $product->color;
            $this->saleStartsAt = $product->sale_starts_at ? $product->sale_starts_at->format('Y-m-d') : null;
            $this->saleEndsAt = $product->sale_ends_at ? $product->sale_ends_at->format('Y-m-d') : null;

            // Campos de produto digital
            $this->isDigital = (bool)$product->is_digital;
            $this->digitalFileName = $product->digital_file_name;
            $this->downloadLimit = $product->download_limit;
            $this->downloadExpiryDays = $product->download_expiry_days;

            $this->showModal = true;
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'message' => 'Erro ao carregar produto: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function save()
    {
        // Validação condicional para produtos digitais
        if ($this->isDigital && !$this->isEditing) {
            $this->validate([
                'digitalFile' => 'required|file|max:51200|mimes:pdf,zip,doc,docx,xls,xlsx,ppt,pptx,mp3,mp4,jpg,jpeg,png,gif',
            ]);
        }

        // Garantir que valores decimais sejam números
        if ($this->price === '') $this->price = 0;
        if ($this->salePrice === '') $this->salePrice = null;
        if ($this->weight === '') $this->weight = null;
        if ($this->downloadLimit === '') $this->downloadLimit = null;
        if ($this->downloadExpiryDays === '') $this->downloadExpiryDays = null;

        // Validação padrão
        $this->validate();

        try {
            if ($this->isEditing) {
                $product = Product::findOrFail($this->productId);
            } else {
                $product = new Product();
                $product->slug = Str::slug($this->name);
            }

            $product->name = $this->name;
            $product->description = $this->description;
            $product->price = $this->price;
            $product->sale_price = $this->salePrice;
            $product->stock = $this->stock;
            $product->category_id = $this->categoryId ?: null;
            $product->featured = (bool)$this->featured;
            $product->status = $this->status;
            $product->sku = $this->sku;
            $product->weight = $this->weight;
            $product->color = $this->color;
            $product->sale_starts_at = $this->saleStartsAt;
            $product->sale_ends_at = $this->saleEndsAt;

            // Campos de produto digital
            $product->is_digital = (bool)$this->isDigital;

            // Se for um produto digital, garantir que o nome do arquivo seja definido
            if ($this->isDigital) {
                $product->download_limit = $this->downloadLimit;
                $product->download_expiry_days = $this->downloadExpiryDays;
                $product->digital_file_name = $this->digitalFileName;

                // Upload do arquivo digital
                if ($this->digitalFile) {
                    try {
                        $filePath = $this->digitalFile->store('digital_products', 'public');
                        $product->digital_file = Storage::url($filePath);

                        // Se o nome do arquivo não foi fornecido, use o nome original
                        if (empty($this->digitalFileName)) {
                            $product->digital_file_name = $this->digitalFile->getClientOriginalName();
                        }
                    } catch (\Exception $e) {
                        $this->addError('digitalFile', 'Erro ao fazer upload do arquivo digital: ' . $e->getMessage());
                        throw $e;
                    }
                }
            } else {
                // Se não for digital, limpar os campos relacionados
                $product->digital_file = null;
                $product->digital_file_name = null;
                $product->download_limit = null;
                $product->download_expiry_days = null;
            }

            // Upload da imagem principal
            if ($this->image) {
                try {
                    $imagePath = $this->image->store('products', 'public');
                    $product->image = Storage::url($imagePath);
                } catch (\Exception $e) {
                    $this->addError('image', 'Erro ao fazer upload da imagem principal: ' . $e->getMessage());
                    throw $e;
                }
            }

            $product->save();

            // Upload de imagens adicionais
            if (!empty($this->additionalImages) && count($this->additionalImages) > 0) {
                foreach ($this->additionalImages as $index => $image) {
                    try {
                        $imagePath = $image->store('products', 'public');

                        ProductImage::create([
                            'product_id' => $product->id,
                            'url' => Storage::url($imagePath),
                            'is_main' => false,
                        ]);
                    } catch (\Exception $e) {
                        $this->addError('additionalImages.' . $index, 'Erro ao fazer upload da imagem adicional: ' . $e->getMessage());
                        // Continua tentando salvar as outras imagens
                    }
                }
            }

            $this->showModal = false;
            $this->resetForm();

            $this->dispatch('notify', [
                'message' => $this->isEditing ? 'Produto atualizado com sucesso!' : 'Produto criado com sucesso!',
                'type' => 'success'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'message' => 'Erro ao salvar produto: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function confirmDelete($id)
    {
        $this->confirmingDelete = true;
        $this->deleteId = $id;
    }

    public function delete()
    {
        try {
            $product = Product::findOrFail($this->deleteId);

            // Excluir imagens adicionais
            foreach ($product->images as $image) {
                // Remover arquivo físico
                $path = str_replace('/storage', 'public', $image->url);
                Storage::delete($path);

                // Excluir registro
                $image->delete();
            }

            // Remover imagem principal
            if ($product->image) {
                $path = str_replace('/storage', 'public', $product->image);
                Storage::delete($path);
            }

            $product->delete();

            $this->confirmingDelete = false;
            $this->deleteId = null;

            $this->dispatch('notify', [
                'message' => 'Produto excluído com sucesso!',
                'type' => 'success'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'message' => 'Erro ao excluir produto: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function resetForm()
    {
        // Campos básicos
        $this->productId = null;
        $this->name = '';
        $this->description = '';

        // Valores numéricos - inicializar com valores apropriados
        $this->price = 0;
        $this->salePrice = null;
        $this->weight = null;
        $this->stock = 0;

        // Outros campos
        $this->categoryId = '';
        $this->featured = false;
        $this->status = 'active';
        $this->sku = '';
        $this->color = '';
        $this->saleStartsAt = null;
        $this->saleEndsAt = null;
        $this->image = null;
        $this->additionalImages = [];

        // Campos de produto digital
        $this->isDigital = false;
        $this->digitalFile = null;
        $this->digitalFileName = '';
        $this->downloadLimit = null;
        $this->downloadExpiryDays = null;

        $this->resetErrorBag();
    }

    public function render()
    {
        $query = Product::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->categoryFilter) {
            $query->where('category_id', $this->categoryFilter);
        }

        $query->orderBy($this->sortBy, $this->sortDirection);

        $products = $query->paginate($this->perPage);
        $categories = Category::orderBy('name')->get();

        return view('livewire.admin.product-manager', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
