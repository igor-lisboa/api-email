<?php

namespace App\Http\Controllers;

use App\Models\Email;
use Illuminate\Validation\Rule;

class EmailController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $model = new Email;
        parent::__construct(
            $model,
            [
                'email' => [
                    'email',
                    'required',
                    Rule::unique($model->getTable())->where(function ($query) {
                        return $query->where('user_uid', '39493x3');
                    }),
                    'max:100'
                ],
                'name' => ['string', 'nullable', 'max:80']
            ]
        );
    }
}
