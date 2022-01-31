<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

//dibawah ini ni package2 atau helper helper yang dasar gitu, jadi baiknya mau dipake atau engga ya tetep panggil aja dulu

use App\Http\Requests\Service\StoreServiceRequest;
use App\Http\Requests\Service\UpdateServiceRequest;

//fungsinya untuk untuk nyimpen file di storage laravel, jangan lupa di storage:link
use Illuminate\Support\Facades\Storage;

//untuk query builder
use Illuminate\Support\Facades\DB;

//kali aja dari request yang udah didefinisikan ada yang kelewat jadi mending panggil aja pake
use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use File;
use Auth;

use App\Models\Service;
use App\Models\AdvantageService;
use App\Models\Tagline;
use App\Models\AdvantageUser;
use App\Models\ThumbnailService;
use App\Models\Order;
use App\Models\User;

class ServiceController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //menampilkan data service sesuai user yang login dan datanya berurut berdasarkan created at secara descending
        //kenapa get karena datanya kan lebih dari satu
        $services = Service::where('users_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();

        return view('pages.Dashboard.service.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.Dashboard.service.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['users_id'] = Auth::user()->id;

        //add data to service
        $service = service::create($data);

        //add data to advantage service
        foreach ($data['advantage_service'] as $key => $value) {
            $advantage_service = new AdvantageService;
            $advantage_service->service_id = $service->id;
            $advantage_service->advantage->id = $value;
            $advantage_service->save();
        }

        //add data to advantage user
        foreach ($data['advantage_user'] as $key => $value) {
            $advantage_user = new AdvantageUser;
            $advantage_user->service_id = $service->id;
            $advantage_user->advantage_id = $value;
            $advantage_user->save();
        }

        //add data to thumbnail service
        //disini data yang disimpan di database itu path nya saja, karena file nya sendiri disimpan pada storage laravel
        if ($request->hasFile('thumbnail')) {
            foreach ($request->file('thumbnail') as $file) {
                $path = $file->store(
                    'assets/service/thumbnail'. 'public'
                );

                $thumbnail_service = new ThumbnailService;
                $thumbnail_service->service_id = $service['id'];
                $thumbnail_service->thumbnail = $path;
                $thumbnail_service->save();
            }
        }

        //add data to tagline
        foreach ($data['tagline'] as $key => $value) {
            $tagline = new Tagline;
            $tagline->service_id = $service->id;
            $tagline->tagline = $value;
            $tagline->save();
        }

        toast()->success('Berhasil di save');
        return redirect()->route('member.service.index');
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
        return view('pages.Dashboard.service.edit');
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
