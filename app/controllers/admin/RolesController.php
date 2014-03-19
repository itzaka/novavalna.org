<?php
namespace Admin;
use BaseController;
use Role, Permission, Answer, Request,  View, Response, Validator, Input, Redirect, App, Language, Position;

class rolesController extends BaseController {
	public function __construct() {
		$this->beforeFilter('manage-users', array('only' => array('index', 'show', 'edit', 'update', 'destroy')));
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		$roles = Role::all();
		if (Request::ajax()) {
			return Response::json(array('aaData' => $roles->toArray()));
		}
		
        return View::make('admin.users.roles.index', compact('roles'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$permissions = Permission::all();
        return View::make('admin.users.roles.create', compact('permissions'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make(Input::all(), array('name' => 'required'));
		if ($validator->fails())
			return Redirect::to('admin/roles/create')->withErrors($validator)->withInput();
		

		$role 	= new Role;

		$role->name = Input::get('name');
		$role->save();
		$permissions = Input::get('permissions');
		if ($permissions == null)
			$permissions = array();
		$role->perms()->sync($permissions);			
		return Redirect::to('admin/roles');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$role = role::find($id);
        return View::make('admin.roles.show', compact('role'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$role = role::find($id);
		if (empty($role))
			App::abort(404);
		$permissions = Permission::all();
        return View::make('admin.users.roles.edit', compact('role', 'permissions'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$validator = Validator::make(Input::all(), array('name' => 'required'));
		if ($validator->fails())
			return Redirect::to('admin/roles/'.$id.'/edit')->withErrors($validator)->withInput();
		

		$role 	= Role::find($id);

		$role->name = Input::get('name');
		$role->save();
		$permissions = Input::get('permissions');
		if ($permissions == null)
			$permissions = array();
		$role->perms()->sync($permissions);			
		return Redirect::to('admin/roles');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$role = role::find($id);
		if (empty($role))
			return Response::json(array('success' => false));
		$role->delete();
		return Response::json(array('success' => true));
	}

}
