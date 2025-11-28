<?php

namespace App\Exports;

use App\Models\Habilidad;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class HabilidadesExport implements FromView
{
    use Exportable;

    protected $filtros;

    public function __construct($filtros = [])
    {
        $this->filtros = $filtros;
    }

    public function view(): View
    {
        $query = Habilidad::query()->with(['usuario', 'categoria']);

        // Aplicar filtros
        if (!empty($this->filtros['estado'])) {
            $query->where('estado', $this->filtros['estado']);
        }

        if (!empty($this->filtros['categoria'])) {
            $query->where('categoria_id', $this->filtros['categoria']);
        }

        $habilidades = $query->orderBy('created_at', 'desc')->get();

        return view('admin.exports.habilidades', [
            'habilidades' => $habilidades
        ]);
    }
}
