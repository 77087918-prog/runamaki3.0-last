<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\View\View;

class UsuariosExport
{
    protected $filtros;

    public function __construct($filtros = [])
    {
        $this->filtros = $filtros;
    }

    public function view(): View
    {
        $query = User::query()->with(['habilidadesOfrecidas', 'valoracionesRecibidas']);

        // Aplicar filtros
        if (!empty($this->filtros['buscar'])) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->filtros['buscar'] . '%')
                  ->orWhere('email', 'like', '%' . $this->filtros['buscar'] . '%');
            });
        }

        if (!empty($this->filtros['estado'])) {
            $query->where('estado', $this->filtros['estado']);
        }

        if (!empty($this->filtros['rol'])) {
            $query->where('rol', $this->filtros['rol']);
        }

        $usuarios = $query->orderBy('created_at', 'desc')->get();

        return view('admin.exports.usuarios', [
            'usuarios' => $usuarios
        ]);
    }
}
