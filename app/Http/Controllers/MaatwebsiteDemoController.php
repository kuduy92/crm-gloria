<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// use Input;
use Illuminate\Support\Facades\Input;
use App\Post;
use DB;
use Excel;
class MaatwebsiteDemoController extends Controller
{
	public function index()
	{
			$posts = Post::orderBy('id', 'desc')->get();

			return view('posts.index', ['posts' => $posts]);
	}

	public function downloadExcel($type)
	{
		$data = Post::get()->toArray();
		return Excel::create('crm-gloria', function($excel) use ($data) {
			$excel->sheet('mySheet', function($sheet) use ($data)
	        {
				$sheet->fromArray($data);
	        });
		})->download($type);
	}
	public function importExcel()
	{
		if(Input::hasFile('import_file')){
			$path = Input::file('import_file')->getRealPath();
			$data = Excel::load($path, function($reader) {
			})->get();
			if(!empty($data) && $data->count()){
				foreach ($data as $key => $value) {
					$insert[] = ['nama' => $value->nama, 'no_hp' => $value->no_hp, 'produk' => $value->produk, 'tgl_beli' => $value->tgl_beli, 'konter' => $value->konter];
				}
				if(!empty($insert)){
					DB::table('posts')->insert($insert);
					dd('Insert Record successfully.');
				}
			}
		}
		return back();
	}
}
