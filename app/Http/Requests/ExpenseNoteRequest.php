<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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
        if ($this->isMethod('post')) {
            // Tous les champs sont requis
            return [
                'note_date' => 'required|date',
                'amount' => 'required|numeric|min:0',
                'type' => 'required|in:essence,péage,repas,conférence',
                'company_id' => 'required|exists:companies,id',
            ];
        }
    
        if ($this->isMethod('put')) {
            // Les champs sont optionnels
            return [
                'note_date' => 'sometimes|date',
                'amount' => 'sometimes|numeric|min:0',
                'type' => 'sometimes|in:essence,péage,repas,conférence',
                'company_id' => 'sometimes|exists:companies,id',
            ];
        }
    
        return [];
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


    /**
     * Personnalise la réponse des erreurs de validation pour éviter les doublons.
     *
     * @param Validator $validator
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 422));
    }

}
