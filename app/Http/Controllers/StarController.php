<?php

namespace App\Http\Controllers;

use App\Models\Star;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Psr\Log\LoggerInterface;

class StarController extends Controller
{
    // Create a specific log file for errors
    private LoggerInterface $logger;

    public function __construct()
    {
        $this->logger = Log::channel('stars');
    }

    /**
     * Validate basic inputs to create or update a star
     *
     * @param Request $request
     *
     * @return Request
     * @throws Exception
     */
    private function validateStarRequest(Request $request): Request
    {
        // Validate for with Laravel Validator
        $validator = Validator::make($request->all(), [
            'first_name'    => 'required|alpha_dash|max:50',
            'last_name'     => 'required|alpha_dash|max:50',
            'img_path'      => 'required|string',
            'description'   => 'required|string'
        ]);

        // if an error occurs, log it and return it to the client
        if($validator->fails()){
            throw new Exception($validator->errors()->first());
        }

        return $request;
    }

    /**
     * Display a listing of Stars.
     *
     * @return Response
     */
    public function index(): Response
    {
        $starColl = Star::all();
        // if there is no record
        if($starColl->isEmpty()){
            $this->logger->info('No stars found');
            return new Response('', 404);
        }

        $starColl->each(function ($item){
            $item->img = $this->getImgName($item->img_path);
        });

        return new Response($starColl->toArray(), 200);
    }

    /**
     * @param string $imgPath
     *
     * @return string
     */
    private function getImgName(string $imgPath): string
    {
        return substr($imgPath, strrpos($imgPath, '\\') + 1);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request): Response
    {
        try {
            // validate form
            $request = $this->validateStarRequest($request);
        }catch (Exception $e){
            $this->logger->error($e->getMessage());
            return new Response($e->getMessage(), 400);
        }

        // Save new record and verify there is no error
        $star = new Star();
        $star->first_name = $request->first_name;
        $star->last_name = $request->last_name;
        $star->img_path = $request->img_path;
        $star->description = $request->description;

        try {
            // try to save
            $star->save();
        }catch (QueryException $e){
            $this->logger->error($e->getMessage());
            return new Response('', 500);
        }

        // return http ok
        return new Response($star->toArray());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(int $id): Response
    {
        try {
            $star = Star::getFromId($id);
        }catch (Exception $e){
            $this->logger->error($e->getMessage());
            return new Response('', 404);
        }

        $star->img = $this->getImgName($star->img_path);

        return new Response($star->toArray());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int    $id
     *
     * @return Response
     */
    public function update(Request $request, int $id): Response
    {
        try {
            // validate form
            $request = $this->validateStarRequest($request);
            // get star by ID
            $star = Star::getFromId($id);
        }catch (Exception $e){
            $this->logger->error($e->getMessage());
            return new Response($e->getMessage(), 400);
        }

        // We can't modify first_name and last_name values because these properties are not fillable
        $star->update($request->all());
        // update and save
        if(!$star->save()){
            $this->logger->error('Error while saving data: '.$request->all());
            return new Response('', 500);
        }

        return new Response($star->toArray());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(int $id): Response
    {
        try {
            // get from ID
            $star = Star::getFromId($id);
        }catch (Exception $e){
            $this->logger->error($e->getMessage());
            return new Response($e->getMessage(), 400);
        }

        // try to delete
        if(!$star->delete()){
            $this->logger->error("Impossible to delete star id $id");
            return new Response('', 500);
        }

        return new Response('', 200);
    }
}
