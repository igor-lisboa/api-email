<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\Eloquent\Model;
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
     * Retorno padrao para as resposta para API
     * @param array $data
     * @param bool $status
     * @param string $message
     * @param int $status_code
     */
    public function responseApi($data, bool $status = true, string $message = '', int $status_code = 200)
    {
        return response()->json([
            'success' => $status,
            'message' => $message,
            'data' => $data
        ], $status_code);
    }

    public function responseApiException(Exception $e, Request $request, string $message, int $code = 500)
    {
        Log::error($e->getMessage());

        $exception = [
            'message' => $e->getMessage(),
            'code' => $e->getCode()
        ];

        if (config('app.debug', false)) {
            $exception['trace_as_string'] = $e->getTraceAsString();
            $exception['file'] = $e->getFile();
            $exception['line'] = $e->getLine();
        }

        return $this->responseApi([
            'exception' => $exception,
            'request' => $request->toArray()
        ], false, $message, $code);
    }

    /**
     * @param Request $request
     */
    public function index(Request $request)
    {
        try {
            $per_page = $request->input('per_page') ?? 15;

            $data = $this->model::paginate($per_page);

            if (!empty($this->indexWith)) {
                $data->load($this->indexWith);
            }

            return $this->responseApi($data, true, __('custom.index.success'));
        } catch (Exception $e) {
            return $this->responseApiException($e, $request, __('custom.index.fail'));
        }
    }

    /**
     * Busca avancada
     * @param Request $request
     */
    public function search(Request $request)
    {
        try {
            $where = $request->has('where') ? $request->get('where') : [];
            $joins = $request->has('join') ? $request->get('join') : [];
            $selects = $request->has('select') ? $request->get('select') : ["*"];
            $orderBy = $request->has('orderBy') ? $request->get('orderBy') : [];
            $groupBy = $request->has('groupBy') ? $request->get('groupBy') : [];

            // model config
            $model = new $this->model;
            $tableDefault = $model->getTable();

            // start query
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
                    case "where":
                        $query->where("{$tableAndColumn}", $item['operation'], $item['value']);
                        break;
                    case "orWhere":
                        $query->orWhere("{$tableAndColumn}", $item['operation'], $item['value']);
                        break;
                    case "whereNull":
                        $query->whereNull("{$tableAndColumn}");
                        break;
                    case "whereNotNull":
                        $query->whereNotNull("{$tableAndColumn}");
                        break;
                    case "whereIn":
                        $query->whereIn("{$tableAndColumn}", $item['value']);
                        break;
                    case "whereNotIn":
                        $query->whereNotIn("{$tableAndColumn}", $item['value']);
                        break;
                    default:
                        throw new Exception('Unexpected value type');
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
                $query->orderBy($orderBy['column'], isset($orderBy['type']) ? $orderBy['type'] : 'DESC');
            }

            $query->orderByDesc("{$tableDefault}.created_at");

            if (!empty($groupBy) && isset($groupBy['column'])) {
                $query->groupBy($groupBy['column']);
            }


            $per_page = $request->input('per_page') ?? 15;

            $data = $query->paginate($per_page);

            if (!empty($this->indexWith)) {
                $data->load($this->indexWith);
            }

            return $this->responseApi($data, true, __('custom.search.success'));
        } catch (Exception $e) {
            return $this->responseApiException($e, $request, __('custom.search.fail'));
        }
    }

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), $this->store_validator_rules, $this->store_validator_messages);

            if ($validator->fails()) {
                return $this->responseApi([
                    'errors' => $validator->errors()
                ], false, __('custom.store.fail'), 422);
            }

            $data = $this->model::create(array_merge($validator->validated(), ['user_uid' => '39493x3']));

            return $this->responseApi($data, true, __('custom.store.success'), 201);
        } catch (Exception $e) {
            return $this->responseApiException($e, $request, __('custom.store.fail'));
        }
    }

    /**
     * @param Request $request
     * @param string $uid
     * @param bool $return_model
     */
    public  function show(Request $request, string $uid, bool $return_model = false)
    {
        try {
            $data = $this->model::where('user_uid', '39493x3')->find($uid);

            if ($return_model) {
                return $data;
            }

            if (is_null($data)) {
                return $this->responseApi([], false,  __('custom.show.not_found'), 404);
            }

            if (!empty($this->showWith)) {
                $data->load($this->showWith);
            }

            return $this->responseApi($data, true, __('custom.show.success'));
        } catch (Exception $e) {
            return $this->responseApiException($e, $request, __('custom.show.fail'));
        }
    }

    /**
     * @param Request $request
     * @param string $uid
     */
    public function update(Request $request, string $uid)
    {
        try {
            $validator = Validator::make($request->all(), $this->update_validator_rules, $this->update_validator_messages);

            if ($validator->fails()) {
                return $this->responseApi([
                    'errors' => $validator->errors()
                ], false, __('custom.update.fail'), 422);
            }

            $model = $this->show($request, $uid, true);

            if (is_null($model)) {
                return $this->responseApi([], false,  __('custom.update.not_found'), 404);
            }

            $model->update($request->except('user_uid'));

            return $this->responseApi($model, true, __('custom.update.success'));
        } catch (Exception $e) {
            return $this->responseApiException($e, $request, __('custom.update.fail'));
        }
    }

    /**
     * @param string $uid
     * @param Request $request
     */
    public function destroy(string $uid, Request $request)
    {
        try {
            $model = $this->show($request, $uid, true);

            if (is_null($model)) {
                return $this->responseApi([], false,  __('custom.destroy.not_found'), 404);
            }

            $model->delete();

            return $this->responseApi([], true, __('custom.destroy.success'));
        } catch (Exception $e) {
            return $this->responseApiException($e, $request, __('custom.destroy.fail'));
        }
    }
}
