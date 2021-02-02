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
     * Retorno padrao para as resposta para API
     * @param array $data
     * @param bool $status
     * @param string $message
     * @param int $status_code
     * @return JsonResponse
     */
    public function responseApi($data, bool $status = true, string $message = '', int $status_code = 200): JsonResponse
    {
        return response()->json([
            'success' => $status,
            'message' => $message,
            'data' => $data
        ], $status_code);
    }

    public function responseApiException(Exception $e, Request $request, string $message, int $code = 500): JsonResponse
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
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
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
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
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
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
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
     * @return JsonResponse
     */
    public  function show(Request $request, string $uid): JsonResponse
    {
        try {
            $data = $this->model::where('user_uid', '39493x3')->find($uid);

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
     * @return JsonResponse
     */
    public function update(Request $request, string $uid): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), $this->update_validator_rules, $this->update_validator_messages);

            if ($validator->fails()) {
                return $this->responseApi([
                    'errors' => $validator->errors()
                ], false, __('custom.update.fail'), 422);
            }

            $show_return = $this->show($request, $uid)->getData();

            if ($show_return->success === false) {
                return $show_return;
            }

            $data = $show_return->data;
            $data->update($request->all());

            return $this->responseApi($data, true, __('custom.update.success'));
        } catch (Exception $e) {
            return $this->responseApiException($e, $request, __('custom.update.fail'));
        }
    }

    /**
     * @param string $uid
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(string $uid, Request $request): JsonResponse
    {
        try {
            $show_return = $this->show($request, $uid);
            $show_return_decoded = json_decode($show_return, true);

            if ($show_return_decoded['success'] === false) {
                return $show_return;
            }

            $data = $show_return_decoded['data'];
            $data->delete();

            return $this->responseApi([], true, __('custom.destroy.success'));
        } catch (Exception $e) {
            return $this->responseApiException($e, $request, __('custom.destroy.fail'));
        }
    }
}
