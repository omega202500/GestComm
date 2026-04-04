<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Produits extends Model
{
    use HasFactory;

    protected $table = 'produits';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nom',
        'categorie',
        'prix_unitaire',
        'stock',
        'unite',
        'photo'  // Ajout de la colonne photo
    ];

    protected $casts = [
        'prix_unitaire' => 'decimal:2',
        'stock' => 'integer',
        'created_at' => 'datetime'
    ];

    protected $attributes = [
        'stock' => 0,
        'unite' => 'pièce'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public static function rules($id = null)
    {
        return [
            'nom' => 'required|string|max:200|unique:produits,nom,' . $id . ',id',
            'categorie' => 'required|string|max:50',
            'prix_unitaire' => 'required|numeric|min:0',
            'stock' => 'integer|min:0',
            'unite' => 'nullable|string|max:50',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Validation pour la photo
        ];
    }

    public static function validationMessages()
    {
        return [
            'nom.required' => 'Le nom du produit est obligatoire',
            'nom.unique' => 'Ce nom de produit existe déjà',
            'prix_unitaire.min' => 'Le prix unitaire doit être positif',
            'stock.min' => 'Le stock ne peut pas être négatif',
            'photo.image' => 'Le fichier doit être une image',
            'photo.mimes' => 'Format accepté: jpeg, png, jpg, gif',
            'photo.max' => 'La photo ne doit pas dépasser 2 Mo'
        ];
    }

    // Accessor pour l'URL de la photo
    public function getPhotoUrlAttribute()
    {
        if ($this->photo && Storage::disk('public')->exists($this->photo)) {
            return Storage::url($this->photo);
        }
        return asset('images/default-product.png'); // Image par défaut
    }

    // Supprimer la photo lors de la suppression du produit
    protected static function booted()
    {
        static::deleting(function ($produit) {
            if ($produit->photo && Storage::disk('public')->exists($produit->photo)) {
                Storage::disk('public')->delete($produit->photo);
            }
        });
    }

}
