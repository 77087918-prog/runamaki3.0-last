<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class MotivationalBanner extends Component
{
    public array $messages;
    public array $dailyMessage;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->messages = [
            ['text' => 'Comparte tu conocimiento y transforma vidas', 'color' => 'from-blue-600 to-cyan-500'],
            ['text' => 'El trueque de habilidades fortalece la comunidad', 'color' => 'from-green-600 to-teal-500'],
            ['text' => 'Aprende algo nuevo cada día con Runa Maki', 'color' => 'from-purple-600 to-pink-500'],
            ['text' => 'Cada trueque es una oportunidad de crecimiento', 'color' => 'from-orange-600 to-red-500'],
            ['text' => 'Tu experiencia es valiosa para alguien más', 'color' => 'from-indigo-600 to-purple-500'],
        ];

        $this->dailyMessage = $this->messages[now()->dayOfYear % count($this->messages)];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.motivational-banner');
    }
}
