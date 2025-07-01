<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MenuItem;

class Menu extends Model
{
    protected $fillable = ['name', 'location'];

    public function items()
    {
        return $this->hasMany(MenuItem::class)->orderBy('order');
    }
}
