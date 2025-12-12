<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $fillable = ['username', 'password', 'role', 'school_id'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function managedSchools()
    {
        return $this->belongsToMany(School::class, 'admin_school');
    }
}
