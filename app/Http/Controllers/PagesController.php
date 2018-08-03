<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Classes\getPrices;



class PagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function __construct()
    {
        $this->middleware('auth', ['except' => ['home']]);
    }


    public function test()
    {
        
        $prices = new getPrices();

        return $prices->fetchPrices();
    }


    public function index()
    {
        return view('pages.index');
    }

    public function home()
    {
        return view('pages.home');
    }

    public function buy()
    {
        $getPrices = new getPrices(); 

        
        $getPrices->fetchPrices();

        
        return view('pages.buy');

        //when clicking the pay button the pricges will be updated and in case of difference with the price
        //on the buy page an alert with the new price offering to accept or cancel will display
    }


    public function sell()
    {
        $getPrices = new getPrices(); 

        
        $getPrices->fetchPrices();

        return view('pages.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
