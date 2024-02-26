<?php

namespace App\Http\Controllers;

use App\Models\Star;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Psr\Log\LoggerInterface;

class StarController extends Controller
{
    private LoggerInterface $logger;

    public function __construct()
    {
        $this->logger = Log::channel('stars');
    }

    /**
     * Display a listing of Stars.
     *
     * @return Response
     */
    public function index(): Response
    {
        $starColl = Star::all();
        if($starColl->isEmpty()){
            $this->logger->info('No stars found');
            return \response('', 404);
        }

        return new Response($starColl->toArray(), 200);
    }

//    /**
//     * Show the form for creating a new resource.
//     *
//     * @return Response
//     */
//    public function create()
//    {
//        //
//    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request): Response
    {
        $validator = Validator::make($request->all(), [
            'first_name'    => 'required|alpha_dash|max:50',
            'last_name'     => 'required|alpha_dash|max:50',
            'img_path'      => 'required|string',
            'description'   => 'required|string'
        ]);

        // if an error occurs, log it and return it to the client
        if($validator->fails()){
            $this->logger->error($validator->errors()->first());
            return \response($validator->errors()->first(), 400);
        }

        // Save new record and verify there is no error
        $star = new Star($request->all());
        if(!$star->save()){
            $this->logger->error('Error while saving data: '.$request->all());
            return \response('', 500);
        }

        // return http ok
        return \response('', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int    $id
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
