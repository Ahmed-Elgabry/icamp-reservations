<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepositoryInterface;
use phpDocumentor\Reflection\Types\Collection;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

abstract class BaseRepository implements BaseRepositoryInterface
{

    protected $model;

    /**
     * insert new model to database
     * @params $data => array of columns names and its values
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }
    
    public function getMax($column)
    {
        return $this->model->max($column);
    }

    /**
     * insert more than 1 model to database
     * @params $data => array of columns names and its values
     */
    public function CreateMulti(array $data)
    {
        return $this->model->insert($data);
    }


    /**
     * updating a model in the database
     * @params $id => row id && $data => array of columns names and its values
     *
     */
    public function update(array $data, $id)
    {
        return $this->model->findOrFail($id)->update($data);
    }

    public function updateWhere(array $data, array $data2)
    {
        return $this->model->where($data2)->update($data);
    }


    /**
     * retrieve all the row for the given model
     * @params OPTIONAL $orderBy with the column name && dir
     */
    public function getAll($orderBy = ['column' => 'id', 'dir' => 'DESC'])
    {
        return $this->model->orderBy($orderBy['column'], $orderBy['dir'])->get();
    }

    public function getAllActive($orderBy = ['column' => 'id', 'dir' => 'DESC'])
    {
        return $this->model->active()->orderBy($orderBy['column'], $orderBy['dir'])->get();
    }

    public function getAllWhere(array $data , $orderBy = ['column' => 'id', 'dir' => 'DESC'])
    {
        return $this->model->where($data)->orderBy($orderBy['column'], $orderBy['dir'])->get();
    }

    /**
     * retrieve all the row for the given model with a conditional relation
     * @params OPTIONAL $orderBy with the column name && dir
     */
    public function whereHas($relation, array $data, array $data2 = [], $orderBy = ['column' => 'id', 'dir' => 'DESC'])
    {
        return $this->model->whereHas($relation, function($q) use($data){
            $q->where($data);
        })->where($data2)->orderBy($orderBy['column'], $orderBy['dir'])->get();
    }

    /**
     * retrieve all the row for the given model with relation and a conditional relation
     * @params OPTIONAL $orderBy with the column name && dir
     */
    public function whereHasWith(array $with, $relation, array $data, array $data2 = [], $orderBy = ['column' => 'id', 'dir' => 'DESC'])
    {
        return $this->model->with($with)->whereHas($relation, function($q) use($data){
            $q->where($data);
        })->where($data2)->orderBy($orderBy['column'], $orderBy['dir'])->get();
    }

    public function whereHasWithFirst(array $with, $relation, array $data, array $data2 = [], $orderBy = ['column' => 'id', 'dir' => 'DESC'])
    {
        return $this->model->with($with)->whereHas($relation, function($q) use($data){
            $q->where($data);
        })->where($data2)->orderBy($orderBy['column'], $orderBy['dir'])->first();
    }

    /**
     * retrieve all the row for the given model with relation and a conditional relation length
     * @params OPTIONAL $orderBy with the column name && dir, $data2
     */
    public function hasCount($relation, $operation, $condition, array $with, array $data2 = [], $orderBy = ['column' => 'id', 'dir' => 'DESC'])
    {
        return $this->model->has($relation, $operation, $condition)->with($with)->where($data2)->orderBy($orderBy['column'], $orderBy['dir'])->get();
    }

    /**
     * retrieve all the TRASHED row for the given model
     * @params OPTIONAL $orderBy with the column name && dir
     */
    public function getAllTrashed($orderBy = ['column' => 'id', 'dir' => 'DESC'])
    {
        return $this->model->onlyTrashed()->orderBy($orderBy['column'], $orderBy['dir'])->get();
    }

    public function getAllTrashedWhere(array $data , $orderBy = ['column' => 'id', 'dir' => 'DESC'])
    {
        return $this->model->onlyTrashed()->where($data)->orderBy($orderBy['column'], $orderBy['dir'])->get();
    }

    /**
     * restore all the TRASHED row for the given model
     */
    public function restoreAllTrashed()
    {
        return $this->model->withTrashed()->restore();
    }


    /**
     * retrieve all the rows matching the where condition
     * @params OPTIONAL $orderBy with the column name && dir
     */
    public function getWhere(array $data, $orderBy = ['column' => 'id', 'dir' => 'DESC'])
    {
        return $this->model->where($data)->orderBy($orderBy['column'], $orderBy['dir'])->get();
    }
    
    public function getWhereYear($column , $operator ,$val, array $data, $orderBy = ['column' => 'id', 'dir' => 'DESC'])
    {
        return $this->model->whereYear($column , $operator ,$val)->where($data)->orderBy($orderBy['column'], $orderBy['dir'])->get();
    }
    
    public function collectByYear(array $data, $orderBy = ['column' => 'id', 'dir' => 'DESC'])
    {
        return $this->model->where($data)->orderBy($orderBy['column'], $orderBy['dir'])->get()
        ->groupBy(function($date) {
            return Carbon::parse($date->event_date)->format('Y'); // grouping by years
        });
    }
    
    /**
     * retrieve all the row for the given model with the given relations
     * @params OPTIONAL $orderBy with the column name && dir
     * @params $data =>array of the relational models to be retrieved
     * @params $limit => count of rows per page
     */
    public function getWith(array $data, $orderBy = ['column' => 'id', 'dir' => 'DESC'])
    {
        return $this->model->with($data)->orderBy($orderBy['column'], $orderBy['dir'])->get();
    }

    /**
     * retrieve the model paginated with th given relations
     * @params OPTIONAL $orderBy with the column name && dir
     * @params $data =>array of the relational models to be retrieved
     */
    public function paginateWith(array $data, $orderBy = ['column' => 'id', 'dir' => 'DESC'], $limit = 10)
    {
        return $this->model->with($data)->orderBy($orderBy['column'], $orderBy['dir'])->paginate($limit);
    }

    
    /**
     * retrieve the model paginated with th given relations and conditions
     * @params OPTIONAL $orderBy with the column name && dir
     * @params $data =>array of the relational models to be retrieved
     */
    public function paginateWhereWith(array $data, array $with, $orderBy = ['column' => 'id', 'dir' => 'DESC'], $limit = 10)
    {
        return $this->model->with($with)->where($data)->orderBy($orderBy['column'], $orderBy['dir'])->paginate($limit);
    }


    /**
     * retrieve the model paginated
     * @params OPTIONAL $orderBy with the column name && dir
     * @params $limit => count of rows per page
     */
    public function paginate($limit = 10, $orderBy = ['column' => 'id', 'dir' => 'DESC'])
    {
        return $this->model->orderBy($orderBy['column'], $orderBy['dir'])->paginate($limit);
    }

    /**
     * delete row for the given model
     * @params $id => the row id to be deleted
     */
    public function delete($id)
    {
        return $this->model->findOrFail($id)->update(['deleted_at' => Carbon::now(), 'deleted_by' => Auth::user()->id]);
    }

    /**
     * force delete row for the given model
     * @params $id => the row id to be deleted
     */
    public function forceDelete($id)
    {
       $item = $this->model->findOrFail($id);
       return  $item->forceDelete();
    }

    /**
     * restore row for the given model
     * @params $id => the row id to be restored
     */
    public function restoreOneTrashed($id)
    {
        return $this->model->withTrashed()->findOrFail($id)->update(['deleted_at' => NULL , 'updated_at' => Carbon::now(), 'updated_by' => Auth::user()->id ,  'deleted_by' => NULL]);
    }

    public function restoreWhereTrashed(array $data)
    {
        return $this->model->withTrashed()->where($data)->update(['deleted_at' => NULL , 'updated_at' => Carbon::now(), 'updated_by' => Auth::user()->id ,  'deleted_by' => NULL]);
    }

    /**
     * delete row for the given model
     * @params $id => the row id to be deleted
     */
    public function forceDeleteWhere(array $data)
    {
        $items =  $this->model->where($data)->get();
        return $items->each->forceDelete();
    }


    public function deleteForceWhereIn( $col,array $data, $orderBy = ['column' => 'id', 'dir' => 'DESC'])
    {
       $items = $this->model->whereIn($col,$data)->orderBy($orderBy['column'], $orderBy['dir'])->get();
       return $items->each->forceDelete();
    }
    /**
     * delete row for the given model
     * @params $id => the row id to be deleted
     */
    public function deleteWhere(array $data)
    {
        return $this->model->where($data)->update(['deleted_at' => Carbon::now(), 'deleted_by' => Auth::user()->id]);
    }

    /**
     * retrieve one row for the given model
     * @params $id => the row id to be retrieved
     */
    public function findOne($id)
    {
        return $this->model->find($id);
    }

    /**
     * retrieve count of rows of the given model
     */
    public function getCount()
    {
        return $this->model->count();
    }

    public function getCountWhere(array $data)
    {
        return $this->model->where($data)->count();
    }

    public function getArchieveCount()
    {
        return $this->model::onlyTrashed()->count();
    }

    public function getArchieveCountWhere(array $data)
    {
        return $this->model::onlyTrashed()->where($data)->count();
    }

    /**
     * empty all rows the given model
     */
    public function deleteAll()
    {
        return  $this->model::where('id', '>', 0)->forceDelete();
        // return $this->model::query()->delete();
    }

    public function softDeleteAll(){
        
        $this->model::where('id', '>', 0)->update(['deleted_at' => Carbon::now(), 'deleted_by' => Auth::user()->id]);
    }

    public function softDeleteAllWhere(array $data){
        
        $this->model::where('id', '>', 0)->where($data)->update(['deleted_at' => Carbon::now(), 'deleted_by' => Auth::user()->id]);
    }

    public function deleteAllQuery()
    {
        $this->model::onlyTrashed()->forceDelete();
    }

    public function deleteAllQueryWhere(array $data)
    {
        $this->model::onlyTrashed()->where($data)->forceDelete();
    }


    /**
     * retrieve one row for the given model with where condition
     * @params $data => array of where conditions
     */
    public function findWhere(array $data, $orderBy = ['column' => 'id', 'dir' => 'DESC'])
    {
        return $this->model->where($data)->orderBy($orderBy['column'], $orderBy['dir'])->first();
    }


    /**
     * retrieve 1 row for the given model with the given relations
     * @params $id => the row id to be retrieved
     * @params $data =>array of the relational models to be retrieved
     */
    public function findWith($id, array $data)
    {
        return $this->model->with($data)->where('id', $id)->first();
    }

    public function getLastItem(array $data)
    {
        return $this->model->with($data)->latest()->first();
    }

    public function findWhereWith(array $data2, array $data)
    {
        return $this->model->with($data)->where($data2)->first();
    }

    /**
     * retrieve 1 row for the given model with the given relations
     * @params $id => the row id to be retrieved
     * @params $data =>array of the relational models to be retrieved
     */
    public function getWhereWith(array $with, array $data, $orderBy = ['column' => 'id', 'dir' => 'DESC'])
    {
        return $this->model->with($with)->where($data)->orderBy($orderBy['column'], $orderBy['dir'])->get();
    }


    public function getWhereIn( $col,array $data, $orderBy = ['column' => 'id', 'dir' => 'DESC'])
    {
        return $this->model->whereIn($col,$data)->orderBy($orderBy['column'], $orderBy['dir'])->get();
    }

    /**
     * retrieve 1 row for the given model with trashed
     * @params $id => the row id to be retrieved
     * @params $data =>array of the relational models to be retrieved
     */
    public function findWithTrashed($id)
    {
        return $this->model->withTrashed()->where('id', $id)->first();
    }

    public function firstWithTrashed()
    {
        return $this->model->withTrashed()->first();
    }

    public function paginateTrashedWhereWithoutRelation(array $data)
    {
        return $this->model->onlyTrashed()->where($data)->get();
    }

    public function paginateTrashedWithoutRelation()
    {
        return $this->model->onlyTrashed()->get();
    }

    public function paginateWithTrashed(array $relation)
    {
        return $this->model->withTrashed()->with($relation)->get();
    }

    public function paginateTrashed(array $relation)
    {
        return $this->model->onlyTrashed()->with($relation)->get();
    }

    /**
     * retrieve 1 row for the given model with trashed and with gine relations
     * @params $id => the row id to be retrieved
     * @params $data =>array of the relational models to be retrieved
     */
    public function findWithDataAndTrashed($id, array $data)
    {
        return $this->model->withTrashed()->with($data)->where('id', $id)->first();
    }

    /**
     * give permissions to model
     * @params $id => the row id to be retrieved
     * @params $data =>array of permissions to be assigned
     */
    public function givePermissions($id, array $permissions){

        $user = $this->model->find($id);

        return $user->givePermissionTo($permissions);
    }

    /**
     * delete permissions for model
     * @params $id => the row id to be retrieved
     * @params $data =>array of permissions to be deleted
     */
    public function deletePermissions($id, array $permissions){

        $user = $this->model->find($id);

        return $user->revokePermissionTo($permissions);
    }

    /**
     * update permissions for model
     * @params $id => the row id to be retrieved
     * @params $data =>array of permissions to be updated
     */
    public function updatePermissions($id, array $permissions){

        $user = $this->model->find($id);

        return $user->syncPermissions($permissions);
    }

    /**
     * @param array $columns
     * @return Collection
     */
    public function search(array $where = ['id', '>', 0],array $columns, $searchQuery ,$orderBy = ['column' => 'id', 'dir' => 'DESC'])
    {

        $whereArr = [];
        $orWhereArr = [];
        $i = 0;
        foreach ($columns as $column){
            if($i == 0){
                array_push($whereArr , $where);
                array_push($whereArr , [$column, 'LIKE', '%'.$searchQuery.'%']);
            }else{
                array_push($orWhereArr , $where);
                array_push($orWhereArr , [$column, 'LIKE', '%'.$searchQuery.'%']);
            }
            $i++;
        }


        if(count($whereArr) > 0){
            $result = $this->model->where($whereArr)->orderBy($orderBy['column'], $orderBy['dir'])->get();
        }

        if(count($orWhereArr) > 0){
            $result = $this->model->where($whereArr)->orWhere($orWhereArr)->orderBy($orderBy['column'], $orderBy['dir'])->get();
        }

        return $result;

    }

    public function searchWith(array $with, array $where = ['id', '>', 0],array $columns, $searchQuery ,$orderBy = ['column' => 'id', 'dir' => 'DESC'])
    {

        $whereArr = [];
        $orWhereArr = [];
        $i = 0;
        foreach ($columns as $column){
            if($i == 0){
                array_push($whereArr , $where);
                array_push($whereArr , [$column, 'LIKE', '%'.$searchQuery.'%']);
            }else{
                array_push($orWhereArr , $where);
                array_push($orWhereArr , [$column, 'LIKE', '%'.$searchQuery.'%']);
            }
            $i++;
        }


        if(count($whereArr) > 0){
            $result = $this->model->with($with)->where($whereArr)->orderBy($orderBy['column'], $orderBy['dir'])->get();
        }

        if(count($orWhereArr) > 0){
            $result = $this->model->with($with)->where($whereArr)->orWhere($orWhereArr)->orderBy($orderBy['column'], $orderBy['dir'])->get();
        }

        return $result;

    }
    
    public function searchWithWhereHas(array $with, array $where = ['id', '>', 0],array $columns, $searchQuery ,$relation_arr,$var,$arr_data,$orderBy = ['column' => 'id', 'dir' => 'DESC'])
    {

        $whereArr = [];
        $orWhereArr = [];
        $i = 0;
        foreach ($columns as $column){
            if($i == 0){
                array_push($whereArr , $where);
                array_push($whereArr , [$column, 'LIKE', '%'.$searchQuery.'%']);
            }else{
                array_push($orWhereArr , $where);
                array_push($orWhereArr , [$column, 'LIKE', '%'.$searchQuery.'%']);
            }
            $i++;
        }


        if(count($whereArr) > 0){
            $result = $this->model->whereHas($relation_arr,function ($q)use ($var,$arr_data){
                $q->where($arr_data);
            })->with($with)->where($whereArr)->orderBy($orderBy['column'], $orderBy['dir'])->get();
        }

        if(count($orWhereArr) > 0){
            $result = $this->model->whereHas($relation_arr,function ($q)use ($var,$arr_data){
                $q->where($arr_data);
            })->with($with)->where($whereArr)->orWhere($orWhereArr)->orderBy($orderBy['column'], $orderBy['dir'])->get();
        }

        return $result;

    }
}