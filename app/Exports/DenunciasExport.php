<?php

namespace App\Exports;

use App\Models\Denuncia;
use Illuminate\Contracts\View\View;

class DenunciasExport
{
    protected $filtros;

    public function __construct($filtros = [])
    {
        $this->filtros = $filtros;
    }

    public function view(): View
    {
        $query = Denuncia::query()->with(['denunciante', 'denunciado', 'procesadoPor']);

        // Aplicar filtros
        if (!empty($this->filtros['estado'])) {
            $query->where('estado', $this->filtros['estado']);
        }

        if (!empty($this->filtros['tipo'])) {
            $query->where('tipo', $this->filtros['tipo']);
        }

        $denuncias = $query->orderBy('created_at', 'desc')->get();

        return view('admin.exports.denuncias', [
            'denuncias' => $denuncias
        ]);
    }
}
