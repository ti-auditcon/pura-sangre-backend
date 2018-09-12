<?php

namespace App\Traits;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Support\Facades\Cache;
// use Illuminate\Support\Facades\Validator;
// use Illuminate\Pagination\LengthAwarePaginator;

  /**
   * [trait description]
   * @var [type]
   */
  trait ApiResponser
  {
    /**
     * [succesResponse Respuesta exitosa]
     * @param  [type] $data [description]
     * @param  [type] $code [description]
     * @return [type]       [description]
     */
    private function succesResponse($data, $code)
    {
      return response()->json($data, $code);
    }

    /**
     * [errorResponse respuesta errónea]
     * @param  [type] $message [description]
     * @param  [type] $code    [description]
     * @return [type]          [description]
     */
    protected function errorResponse($message, $code)
    {
      return response()->json(['error' => $message, 'code' => $code], $code);
    }

    //
    /**
     * [showAll Función pública pque regresa toda una colección]
     * @param  Collection $collection [description]
     * @param  integer    $code       [description]
     * @return [type]                 [description]
     */
    protected function showAll(Collection $collection, $code = 200)
    {
      if ($collection->isEmpty()) {
        return $this->succesResponse(['data' => $collection], $code);
      }

      $transformer = $collection->first()->transformer;

      // $collection = $this->filtrarDatos($collection, $transformer);
      //
      // $collection = $this->ordenarData($collection, $transformer);
      //
      // $collection = $this->paginate($collection, $transformer);

      // $collection = $this->transformData($collection, $transformer);

      // $collection = $this->cacheResponse($collection);

      return $this->succesResponse($collection, $code);
    }

    /**
     * [showOne Función protegida que regresa toda una instancia]
     * @param  Model  $instance [description]
     * @param  [type] $code     [description]
     * @return [type]           [description]
     */
    protected function showOne(Model $instance, $code)
    {
      $transformer = $instance->transformer;

      $instance = $this->transformData($instance, $transformer);

      return $this->succesResponse($instance, $code);

    }

    // /**
    //  * [filtrarDatos description]
    //  * @param  Collection $collection  [description]
    //  * @param  [type]     $transformer [description]
    //  * @return [type]                  [description]
    //  */
    // public function filtrarDatos(Collection $collection, $transformer)
    // {
    //   // dd(request()->query());
    //   foreach (request()->query() as $indexquery => $valuequery) {
    //     $attribute = $transformer::originalAttribute($indexquery);
    //     // dd($attribute);
    //     if (isset($attribute, $valuequery)) {
    //       $collection = $collection->where($attribute, $valuequery);
    //     }
    //   }
    //   return $collection;
    // }

    // /**
    //  * [ordenarData Función protegida que ordena una colección (ocupada en método "showAll")]
    //  * @param  Collection $collection  [description]
    //  * @param  [type]     $transformer [description]
    //  * @return [type]                  [description]
    //  */
    // protected function ordenarData(Collection $collection, $transformer)
    // {
    //   if (request()->has('sort_by')) {
    //     $attribute = $transformer::originalAttribute(request()->sort_by);
    //
    //     $collection = $collection->sortBy->{$attribute};
    //   }
    //
    //   return $collection;
    //
    // }

    // /**
    //  * [paginate description]
    //  * @param  Collection $collection [description]
    //  * @return [type]                 [description]
    //  */
    // protected function paginate(Collection $collection)
    // {
    //   $rules = [
    //     'per_page' => 'integer|min:2|max:50'
    //   ];
    //
    //   Validator::validate(request()->all(), $rules);
    //
    //   $page = LengthAwarePaginator::resolveCurrentPage();
    //
    //   $perPage = 15;
    //
    //   if (request()->has('per_page')) {
    //     $perPage = (int)request()->per_page;
    //   }
    //
    //   $results = $collection->slice(($page - 1) * $perPage, $perPage)->values();
    //
    //   $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, ['path' => LengthAwarePaginator::resolveCurrentPath()]);
    //
    //   return $paginated->appends(request()->all());

// new LengthAwarePaginator($items, $total, $perPage [, $currentPage, $options])

    }

    // /**
    //  * [transformData Función protegida que transforma los índices de los datos entregados]
    //  * @param  [type] $datos       [description]
    //  * @param  [type] $transformer [description]
    //  * @return [type]              [description]
    //  */
    // protected function transformData($datos, $transformer)
    // {
    //   $transformation = fractal($datos, new $transformer);
    //
    //   return $transformation->toArray();
    //
    // }
