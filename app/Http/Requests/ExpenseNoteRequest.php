<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseNoteRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; 
    }

    /**
     * Règles de validation pour la création et la mise à jour d'une note de frais.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'note_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:essence,péage,repas,conférence',
            'company_id' => 'required|exists:companies,id',
        ];
    }

    /**
     * Messages d'erreur personnalisés.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'note_date.required' => 'La date de la note est obligatoire.',
            'amount.required' => 'Le montant est obligatoire.',
            'amount.numeric' => 'Le montant doit être un nombre.',
            'amount.min' => 'Le montant doit être supérieur ou égal à 0.',
            'type.required' => 'Le type de la note est obligatoire.',
            'type.in' => 'Le type doit être : essence, péage, repas, ou conférence.',
            'company_id.required' => 'L\'identifiant de la société est obligatoire.',
            'company_id.exists' => 'L\'entreprise sélectionnée n\'existe pas.',
        ];
    }
}
