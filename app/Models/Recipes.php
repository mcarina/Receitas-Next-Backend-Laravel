<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recipes extends Model
{
    use HasFactory;
    protected $table = 'recipes';

    protected $fillable = [
        'title',
        'description',
        'preparation_method',
        'category_id',
        'user_id',
        'created_at',
        'updated_at',
    ];    

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relação com a tabela User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // 'user_id' é a chave estrangeira
    }

    // Relação com a tabela Ingredients
    public function ingredients()
    {
        return $this->hasMany(Ingredients::class, 'recipe_id'); // 'recipe_id' é a chave estrangeira em ingredients
    }
}
