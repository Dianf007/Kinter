<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'type',
        'description',
        'icon',
        'color'
    ];
    
    public function dailyReports()
    {
        return $this->hasMany(DailyReport::class);
    }
    
    public function getIconClassAttribute()
    {
        return match($this->type) {
            'code_org' => 'fas fa-code',
            'scratch' => 'fas fa-puzzle-piece',
            'quiz' => 'fas fa-question-circle',
            'reading' => 'fas fa-book',
            'math' => 'fas fa-calculator',
            'science' => 'fas fa-flask',
            'art' => 'fas fa-palette',
            'music' => 'fas fa-music',
            'physical' => 'fas fa-running',
            default => 'fas fa-graduation-cap'
        };
    }
}
