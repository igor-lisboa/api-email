<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /** @var Model $model */
    protected $model;

    /** @var array $store_validator_rules */
    protected $store_validator_rules;

    /** @var array $store_validator_messages */
    protected $store_validator_messages;

    /**
     * Relacionamentos para o método index.
     * @var array
     */
    protected $indexWith = [];

    /**
     * Relacionamentos para o método show.
     * @var array
     */
    protected $showWith = [];

    public function __construct(
        Model $model,
        array $store_validator_rules = [],
        array $store_validator_messages = [],
        array $update_validator_rules = [],
        array $update_validator_messages = [],
        array $indexWith = [],
        array $showWith = []
    ) {
        $this->model = $model;
        $this->store_validator_rules = $store_validator_rules;
        $this->store_validator_messages = $store_validator_messages;
        $this->update_validator_rules = $update_validator_rules;
        $this->update_validator_messages = $update_validator_messages;
        $this->indexWith = $indexWith;
        $this->showWith = $showWith;
    }

    /**
     * Lista registros com ou sem paginação
     * @param Request $request
     */
    public function index(Request $request): JsonResponse
    {
        try {
            /** @var bool $not_paginated */
            $not_paginated = $request->input('not_paginated') ?? false;

            /** @var \Illuminate\Database\Eloquent\Model $data */
            $data = $this->model;

            /** @var \Illuminate\Database\Eloquent\Collection|static[]|\Illuminate\Contracts\Pagination\LengthAwarePaginator $collection */
            $collection = [];

            if ($not_paginated) {
                $collection = $data->all();
            } else {
                /** @var int $per_page */
                $per_page = $request->input('per_page') ?? 15;

                $collection = $data::paginate($per_page);
            }

            if (!empty($this->indexWith)) {
                $data->load($this->indexWith);
            }

            return responseApi($collection->toArray(), true, __('custom.index.success'));
        } catch (Exception $e) {
            return responseApiException($e, $request->toArray(), __('custom.index.fail'));
        }
    }

    /**
     * Busca avancada
     * @param Request $request
     */
    public function search(Request $request): JsonResponse
    {
        try {
            /** @var array $where */
            $where = $request->has('where') ? $request->get('where') : [];

            /** @var array $joins */
            $joins = $request->has('join') ? $request->get('join') : [];

            /** @var array $selects */
            $selects = $request->has('select') ? $request->get('select') : ['*'];

            /** @var array $orderBy */
            $orderBy = $request->has('orderBy') ? $request->get('orderBy') : [];

            /** @var array $groupBy */
            $groupBy = $request->has('groupBy') ? $request->get('groupBy') : [];

            /** @var \Illuminate\Database\Eloquent\Model $model */
            $model = new $this->model;
            $tableDefault = $model->getTable();

            /** @var \Illuminate\Database\Eloquent\Builder $query */
            $query = $model::query();
            if (!empty($joins)) {
                foreach ($joins as $join) {
                    $query->{$join['type']}($join['table'], $join['from'], $join['operation'], $join['to']);
                }
            }

            foreach ($where as $item) {
                $explodeColumn = explode('.', $item['column']);
                $tableAndColumn = $item['column'];
                if (count($explodeColumn) == 1) {
                    $tableAndColumn = "{$item['table']}.{$item['column']}";
                }

                switch ($item['type']) {
                    case 'where':
                        $query->where("{$tableAndColumn}", $item['operation'], $item['value']);
                        break;
                    case 'orWhere':
                        $query->orWhere("{$tableAndColumn}", $item['operation'], $item['value']);
                        break;
                    case 'whereNull':
                        $query->whereNull("{$tableAndColumn}");
                        break;
                    case 'whereNotNull':
                        $query->whereNotNull("{$tableAndColumn}");
                        break;
                    case 'whereIn':
                        $query->whereIn("{$tableAndColumn}", $item['value']);
                        break;
                    case 'whereNotIn':
                        $query->whereNotIn("{$tableAndColumn}", $item['value']);
                        break;
                    default:
                        throw new Exception(__('custom.search.fail.invalidType', ['item' => json_encode($item)]));
                }
            }

            foreach ($selects as $select) {
                if ($select != '*') {
                    $explode = explode('.', $select);
                    if (count($explode) == 1) {
                        $select = "{$tableDefault}.{$select}";
                    }
                }
                $query->addSelect($select);
            }

            if (!empty($orderBy) && isset($orderBy['column'])) {
                $query->orderBy($orderBy['column'], isset($orderBy['type']) ? $orderBy['type'] : 'ASC');
            }

            if (!empty($groupBy) && isset($groupBy['column'])) {
                $query->groupBy($groupBy['column']);
            }

            /** @var int $per_page */
            $per_page = $request->input('per_page') ?? 15;


            /** @var bool $not_paginated */
            $not_paginated = $request->input('not_paginated') ?? false;

            /** @var \Illuminate\Database\Eloquent\Collection|static[]|\Illuminate\Contracts\Pagination\LengthAwarePaginator $data */
            $data = [];

            if ($not_paginated) {
                $data = $query->get();
            } else {
                /** @var int $per_page */
                $per_page = $request->input('per_page') ?? 15;

                $data = $query->paginate($per_page);
            }

            return responseApi((array) $data, true, __('custom.search.success'));
        } catch (Exception $e) {
            return responseApiException($e, $request->toArray(), __('custom.search.fail'));
        }
    }

    /**
     * Guarda novos registros
     * @param Request $request
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), $this->store_validator_rules, $this->store_validator_messages);

            if ($validator->fails()) {
                return responseApi([
                    'errors' => $validator->errors()
                ], false, __('custom.store.fail'), 422);
            }

            $data = $this->model::create($validator->validated());

            return responseApi($data, true, __('custom.store.success'), 201);
        } catch (Exception $e) {
            return responseApiException($e, $request->toArray(), __('custom.store.fail'));
        }
    }

    /**
     * Mostra registro
     * @param Request $request
     * @param string $uid
     */
    public  function show(Request $request, string $uid): JsonResponse
    {
        try {
            $data = $this->model::find($uid);

            if (is_null($data)) {
                return responseApi([], false,  __('custom.show.not_found'), 404);
            }

            if (!empty($this->showWith)) {
                $data->load($this->showWith);
            }

            return responseApi($data, true, __('custom.show.success'));
        } catch (Exception $e) {
            return responseApiException($e, $request->toArray(), __('custom.show.fail'));
        }
    }

    /**
     * Atualiza registro
     * @param Request $request
     * @param string $uid
     */
    public function update(Request $request, string $uid): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), $this->update_validator_rules, $this->update_validator_messages);

            if ($validator->fails()) {
                return responseApi([
                    'errors' => $validator->errors()
                ], false, __('custom.update.fail'), 422);
            }

            $data = $this->model::find($uid);

            if (is_null($data)) {
                return responseApi([], false,  __('custom.update.not_found'), 404);
            }

            $data->update($validator->validated());

            return responseApi($data, true, __('custom.update.success'));
        } catch (Exception $e) {
            return responseApiException($e, $request->toArray(), __('custom.update.fail'));
        }
    }

    /**
     * @param Request $request
     * @param string $uid
     */
    public function destroy(Request $request, string $uid)
    {
        try {
            $data = $this->model::find($uid);

            if (is_null($data)) {
                return responseApi([], false,  __('custom.destroy.not_found'), 404);
            }

            $data->delete();

            return responseApi([], true, __('custom.destroy.success'));
        } catch (Exception $e) {
            return responseApiException($e, $request->toArray(), __('custom.destroy.fail'));
        }
    }
}
