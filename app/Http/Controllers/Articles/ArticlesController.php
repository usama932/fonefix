<?php

namespace App\Http\Controllers\Articles;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $obj_category;
    private $obj_article;

    public function __construct(Category $categoryObject, Article $articleObject)
    {

        $this->obj_category = $categoryObject;
        $this->obj_article = $articleObject;
    }
    public function index()
    {
        return view('admin.articles.index', ['title' => 'Articles']);

    }
    public function getArticles(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'title',
            2 => 'author',
            3 => 'category',
            4 => 'priority',
            5 => 'created_at',
            6 => 'updated_at',
            7 => 'action'
        );

        $totalData = Article::count();
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $articles = Article::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Article::count();
        } else {
            $search = $request->input('search.value');
            $articles = Article::where('title', 'like', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Article::where('title', 'like', "%{$search}%")
                ->count();
        }


        $data = array();

        if ($articles) {
            foreach ($articles as $r) {
                $edit_url = route('articles.edit', $r->id);
                $nestedData['id'] = '<td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="categories[]" value="'.$r->id.'"><span></span></label></td>';
                $nestedData['title'] = $r->title;
                $nestedData['author'] = $r->user->name;
                if ($r->priority == 1) {
                    $nestedData['priority'] = '<span class="label label-lg font-weight-bold  label-light-info label-inline">Active</span>';
                } else {
                    /*label label-danger label-inline mr-2*/
                    $nestedData['priority'] = '<span class="label label-lg font-weight-bold  label-light-danger label-inline">Inactive</span>';
                }
                if($r->categories){
                    $nestedData['category'] = $r->categories->name;
                }else{
                    $nestedData['category'] = '<span class="label label-lg font-weight-bold  label-light-danger label-inline">Not Categorised</span>';
                }
                $nestedData['created_at'] = date('Y-m-d h:m:s a', strtotime($r->created_at));
                $nestedData['updated_at'] = date('Y-m-d h:m:s a', strtotime($r->updated_at));
                $nestedData['action'] = '
                                <div>
                                <td>
                                    <a class="btn btn-sm btn-clean btn-icon" onclick="event.preventDefault();viewInfo('.$r->id.');" title="View Article" href="javascript:void(0)">
                                        <i class="icon-1x text-dark-50 flaticon-eye"></i>
                                    </a>
                                    <a title="Edit Article" class="btn btn-sm btn-clean btn-icon"
                                       href="'.$edit_url.'">
                                       <i class="icon-1x text-dark-50 flaticon-edit"></i>
                                    </a>
                                    <a class="btn btn-sm btn-clean btn-icon" onclick="event.preventDefault();del('.$r->id.');" title="Delete Article" href="javascript:void(0)">
                                        <i class="icon-1x text-dark-50 flaticon-delete"></i>
                                    </a>
                                </td>
                                </div>
                            ';

                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw"			=> intval($request->input('draw')),
            "recordsTotal"	=> intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"			=> $data
        );

        echo json_encode($json_data);

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
