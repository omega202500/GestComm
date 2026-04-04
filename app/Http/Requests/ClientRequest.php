<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Autoriser tous les utilisateurs authentifiés
    }

    public function rules()
    {
        $clientId = $this->route('client') ? $this->route('client')->id : null;
        
        return [
            'nom' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20|unique:clients,telephone,' . $clientId,
            'email' => 'nullable|email|max:255|unique:clients,email,' . $clientId,
            'zone_id' => 'nullable|exists:zones,id',
            'adresse' => 'nullable|string|max:500',
            'solde' => 'nullable|numeric|min:0'
        ];
    }

    public function messages()
    {
        return [
            'nom.required' => 'Le nom du client est obligatoire',
            'nom.string' => 'Le nom doit être une chaîne de caractères',
            'nom.max' => 'Le nom ne doit pas dépasser 255 caractères',
            'telephone.unique' => 'Ce numéro de téléphone est déjà utilisé',
            'email.unique' => 'Cet email est déjà utilisé',
            'email.email' => 'Veuillez saisir un email valide',
            'zone_id.exists' => 'La zone sélectionnée n\'existe pas',
            'solde.numeric' => 'Le solde doit être un nombre',
            'solde.min' => 'Le solde ne peut pas être négatif'
        ];
    }
}