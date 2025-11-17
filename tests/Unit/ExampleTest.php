<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
// Importa la clase base TestCase del framework PHPUnit,
// que provee las funciones necesarias para ejecutar pruebas unitarias. 

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        // Se realiza una afirmación (assertion).
        // PHPUnit verifica que la condición sea verdadera.
        // Si 'true' es efectivamente true, la prueba pasa correctamente.
        $this->assertTrue(true);
    }
}
