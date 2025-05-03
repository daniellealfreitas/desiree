<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

class ProfileProgressBar extends Component
{
    /**
     * Deve ser populado pela view ou rota: ex. <livewire:profile-progress-bar :username="$username" />
     */
    public string $username;

    /**
     * Busca o usuário pelo username informado.
     */
    protected function getUser(): ?User
    {
        return User::where('username', $this->username)->first();
    }

    /**
     * Retorna os campos relevantes da tabela users do banco de dados, excluindo campos técnicos.
     */
    protected function getRelevantFields(): array
    {
        $allFields = Schema::getColumnListing('users');
        $exclude = [
            'id', 'password', 'remember_token', 'created_at', 'updated_at', 'email_verified_at'
        ];
        return array_values(array_diff($allFields, $exclude));
    }

    /**
     * Retorna os valores dos campos relevantes do usuário buscado.
     */
    protected function getUserFieldsValues(): array
    {
        $user = $this->getUser();
        if (!$user) {
            return [];
        }
        $fields = $this->getRelevantFields();
        $values = [];
        foreach ($fields as $field) {
            $values[$field] = $user->{$field} ?? null;
        }
        return $values;
    }

    /**
     * Calcula a porcentagem de preenchimento do perfil, considerando preenchido se o valor NÃO É null.
     */
    public function getProfileCompletionProperty()
    {
        $values = $this->getUserFieldsValues();
        $total = count($values);

        if ($total === 0) {
            return 0;
        }

        $filled = 0;
        foreach ($values as $val) {
            if (!is_null($val)) {
                $filled++;
            }
        }

        return intval(($filled / $total) * 100);
    }

    /**
     * Cor de preenchimento da barra de acordo com a porcentagem.
     */
    public function getProgressColorProperty()
    {
        $percent = $this->profileCompletion;
        if ($percent < 30) {
            return 'bg-red-500';
        } elseif ($percent < 70) {
            return 'bg-yellow-500';
        } else {
            return 'bg-green-500';
        }
    }

    /**
     * Retorna o status preenchido/não preenchido de cada campo, considerando apenas null.
     */
    public function getProfileFieldsStatusProperty()
    {
        $values = $this->getUserFieldsValues();
        $result = [];
        foreach ($values as $key => $val) {
            $result[$key] = !is_null($val);
        }
        return $result;
    }

    public function render()
    {
        return view('livewire.profile-progress-bar', [
            'profileCompletion' => $this->profileCompletion,
            'profileFieldsStatus' => $this->profileFieldsStatus,
            'progressColor'      => $this->progressColor
        ]);
    }
}