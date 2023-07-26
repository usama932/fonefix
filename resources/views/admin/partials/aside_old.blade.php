<nav class="mt-2">
<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false" >
    <!-- Add icons to the links using the .nav-icon class
         with font-awesome or any other icon font library -->


    @if(Auth::user()->role != 4)
    <li class="nav-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'articles') {
        echo 'nav-item-active';
    }?>">

        <a href="{{ route('articles.create') }}" class="nav-link">
            <i class="nav-icon fa fa-edit"></i>
            <p class="nav_p">
                Post Article
            </p>
        </a>
    </li>

    {{--Articles--}}
    
    <li class="nav-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'articles') {
        echo 'nav-item-active';
    }?>">
        <a href="{{ route('articles.index') }}" class="nav-link">
            <i class="nav-icon fas  fa-newspaper "></i>
            <p class="nav_p">
                 Articles

            </p>
        </a>
    </li>
    {{--Priority One Articles--}}
    <li class="nav-item<?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'p-articles') {
        echo 'nav-item-active';
    }?>">
        <a href="{{ route('admin.PriorityOne') }}" class="nav-link">
            <i class="nav-icon fas fa-exclamation-triangle" style="color: red;"></i>
            <p class="nav_p">
                Priority One

            </p>
        </a>
    </li>

    {{--Police One Feeds--}}
    @if(isset($settings['police_one']))
    <li class="nav-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'police') {
        echo 'nav-item-active';
    }?>">
        <a href="{{ route('police.index') }}" class="nav-link">
            <i class="nav-icon fas fa-rss"></i>
            <p class="nav_p">
                Police One News
            </p>
        </a>
    </li>
    @endif
    @endif
    {{-- Article By Category--}}
    <li   class="nav-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'categories') {
        echo ' menu-open active';
    }?> <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'by-cat') {
        echo 'menu-item-open';
    }?>"  @if(Auth::user()->role == 4) style="display: none !important;" @endif >
        <a href="#" class="nav-link " >
            <i class="nav-icon far fa-folder-open"></i>
            <p class="nav_p">
                Categories
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <?php
            use \App\Models\Category;
 use Illuminate\Support\Facades\Request;

            $category = new Category();
            $categories = $category->all();
            /*use \App\Models\Api;
            $api = new Api();
            $data['news'] = $api->getNews('headlines');
            $data['news_sources'] = $api->getAllSources();
            $count = 0;*/

            ?>
            @foreach($categories as $category)
                @if($category->parent_id == null)
                    <li class="nav-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'by-cat') {
                        echo 'active';
                    }?>">
                        <a href="{{ route('admin.getArticlesByCat',[$category->slug]) }}" class="nav-link ">
                            <i class="far fa-circle nav-icon"></i>
                            <p class="nav_p">{{$category->name}}</p>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </li>
    @if(Auth::user()->role != 4)
    {{--  Events--}}
    @if(isset($settings['event_calendar']))
    <li class="nav-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'events') {
        echo 'nav-item-active';
    }?>">
        @php
            $from = date('Y-m-d');
           $to = date('Y-m-d', strtotime("+2 days"));
           $evs = \App\Models\Event::whereBetween('start_time', [$from, $to])->get()->count();
        @endphp
        <a href="{{ route('events.index') }}" class="nav-link">
            <i class="nav-icon fas fa-calendar-alt"></i>
            <p class="nav_p">
                Event Calender  @if($evs > 0)
                    <span class="label label-lg font-weight-bold  label-light-danger label-inline">{{$evs}}</span>
                                    @endif
            </p>
        </a>
    </li>
    @endif
    @endif
    {{-- Roll Call--}}

    @if(Auth::user()->role == 1)
    @if(isset($settings['hours']) or isset($settings['days']) or isset($settings['cad_dashboard']))
    <li class="nav-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'Fleet') {
        echo ' menu-open active';
    }?> <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'vehicles'
        or Request::segment(1) == 'admin' && Request::segment(2) == 'fleet-complaints'
        or Request::segment(1) == 'admin' && Request::segment(2) == 'tickets'
        or Request::segment(1) == 'admin' && Request::segment(2) == 'tickets' && Request::segment(3) == 'create') {
        echo 'menu-item-open';
    }?>">
        <a href="#" class="nav-link ">
            <i class="nav-icon fa fa-users"></i>
            <p class="nav_p">
                Roll Call
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            @if(isset($settings['hours']))
                <li class="nav-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == '72-hours-cad') {
                    echo 'nav-item-active';
                }?>">
                    <a href="{{ route('72-hours-cad') }}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p class="nav_p">72 Hour CAD</p>
                    </a>
                </li>
            @endif
            @if(isset($settings['days']))
                <li class="nav-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == '30-days-arrest-log') {
                    echo 'nav-item-active';
                }?>">
                    <a href="{{ route('30-days-arrest-log') }}" class="nav-link ">
                        <i class="far fa-circle nav-icon"></i>
                        <p class="nav_p">30 Day Arrest Log</p>
                    </a>
                </li>
            @endif
            @if(isset($settings['cad_dashboard']))
                <li class="nav-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'tickets' && Request::segment(3) == 'create') {
                    echo 'nav-item-active';
                }?>">
                    <a href="{{ route('cad_dashboard.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p class="nav_p">CAD Dashboard</p>
                    </a>
                </li>
            @endif
        </ul>
    </li>
    @endif
    @endif
    
    @if(Auth::user()->role == 1 or Auth::user()->role == 2 or Auth::user()->role == 3)
    {{-- Department Links--}}
    <li class="nav-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'Fleet') {
        echo ' menu-open active';
    }?> <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'vehicles'
        or Request::segment(1) == 'admin' && Request::segment(2) == 'fleet-complaints'
        or Request::segment(1) == 'admin' && Request::segment(2) == 'tickets'
        or Request::segment(1) == 'admin' && Request::segment(2) == 'tickets' && Request::segment(3) == 'create') {
        echo 'menu-item-open';
    }?>">
        <a href="#" class="nav-link ">
            <i class="nav-icon fa fa-bookmark"></i>
            <p class="nav_p">
                Department Links
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <?php

            $link = new \App\Models\Link();
            $links = $link->all();
            ?>
            @foreach($links as $link)
                    <li class="nav-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'by-cat') {
                        echo 'active';
                    }?>">
                        <a href="{{url($link->url)}}" title="{{$link->title}}" class="nav-link " target="_blank">
                            <i class="far fa-circle nav-icon"></i>
                            <p class="nav_p">{{$link->name}}</p>
                        </a>
                    </li>
            @endforeach
        </ul>
    </li>
   @endif

    @if(Auth::user()->role == 1 or Auth::user()->role == 2 or Auth::user()->role == 3)
    @if(Auth::user()->document_management == 1)
    {{--Document Management--}}
    <li class="nav-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'Fleet') {
        echo ' menu-open active';
    }?> <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'vehicles'
        or Request::segment(1) == 'admin' && Request::segment(2) == 'fleet-complaints'
        or Request::segment(1) == 'admin' && Request::segment(2) == 'tickets'
        or Request::segment(1) == 'admin' && Request::segment(2) == 'tickets' && Request::segment(3) == 'create') {
        echo 'menu-item-open';
    }?>">
        <a href="#" class="nav-link ">
            <i class="nav-icon fa fa-book"></i>
            <p class="nav_p">
                Document Management
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            @php $flds = \App\Models\Folder::all(); @endphp
            @foreach($flds as $fld)
            <li class="nav-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'folders') {
                echo 'nav-item-active';
            }?>">
                <a href="{{ route('folders.show',$fld->id) }}" class="nav-link ">
                    <i class="far fa-circle nav-icon"></i>
                    <p class="nav_p">{{$fld->name}}</p>
                </a>
            </li>
            @endforeach
            <li class="nav-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'tickets') 
               echo 'nav-item-active';
            ?>">
               <a href="{{ route('tickets.index') }}" class="nav-link ">                             <i class="far fa-circle nav-icon"></i>
                  <p class="nav_p">Dispatch</p>                          </a>
           </li>
          <li class="nav-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'tickets' && Request::segment(3) == 'create') 
                echo 'nav-item-active';
            ?>">
                <a href="{{ route('tickets.create') }}" class="nav-link">
                   <i class="far fa-circle nav-icon"></i>
                   <p class="nav_p">Investigation</p>
                </a>
            </li>
            <li class="nav-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'tickets' && Request::segment(3) == 'create') 
                echo 'nav-item-active';
           ?>">
                <a href="{{ route('tickets.create') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p class="nav_p">Training</p>
                </a>
            </li>
            <li class="nav-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'tickets' && Request::segment(3) == 'create') 
                echo 'nav-item-active';
            ?>">
               <a href="{{ route('tickets.create') }}" class="nav-link">
                   <i class="far fa-circle nav-icon"></i>
                    <p class="nav_p">Patrol</p>
              </a>
          </li>
        </ul>
    </li>
    @endif
   @endif

    @if(Auth::user()->role == 1 or Auth::user()->role == 2 or Auth::user()->role == 3 )
    @if(Auth::user()->fleet_management == 1)
    {{-- Fleet Management--}}
    <li class="nav-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'Fleet') {
        echo ' menu-open active';
    }?> <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'vehicles'
        or Request::segment(1) == 'admin' && Request::segment(2) == 'fleet-complaints'
        or Request::segment(1) == 'admin' && Request::segment(2) == 'tickets'
        or Request::segment(1) == 'admin' && Request::segment(2) == 'tickets' && Request::segment(3) == 'create') {
        echo 'menu-item-open';
    }?>">
        <a href="#" class="nav-link ">
            <i class="nav-icon fas fa-car"></i>
            <p class="nav_p">
                Fleet Management
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'tickets') {
                echo 'active';
            }?>">
                <a href="{{ route('tickets.index') }}" class="nav-link ">
                    <i class="far fa-circle nav-icon"></i>
                    <p class="nav_p">Fleet Dashboard</p>
                </a>
            </li>
            <li class="nav-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'vehicles'
                or Request::segment(1) == 'admin' && Request::segment(2) == 'fleet-complaints') {
                echo 'menu-item-open';
            }?>">
                <a href="#" class="nav-link ">
                    <i class="nav-icon fas fa-circle "></i>
                    <p class="nav_p">
                        Manage Vehicle
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'vehicles') {
                        echo 'menu-item-active';
                    }?>">
                        <a href="{{ route('vehicles.index') }}" class="nav-link ">
                            <i class="far fa-circle nav-icon"></i>
                            <p class="nav_p">Manage Vehicles</p>
                        </a>
                    </li>
                   <!-- <li class="nav-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'fleet-complaints') {
                        echo 'menu-item-active';
                    }?>">
                        <a href="{{ route('fleet-complaints.index') }}" class="nav-link">--}}
                          <i class="far fa-circle nav-icon"></i>--}}
                            <p class="nav_p">Manage Complaints</p>--}}
                       </a>
                    </li> -->
                </ul>
            </li>
            <li class="nav-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'tickets' && Request::segment(3) == 'create') {
                echo 'menu-item-active';
            }?>">
                <a href="{{ route('tickets.create') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p class="nav_p">Create Ticket</p>
                </a>
            </li>
        </ul>
    </li>
    @endif
    @endif
  
    @if(Auth::user()->role == 1 or Auth::user()->role == 2 or Auth::user()->role == 3)
    @if(Auth::user()->support == 1)

    {{-- Support--}}
    <li class="nav-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'Fleet') {
        echo ' menu-open active';
    }?> <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'vehicles'
        or Request::segment(1) == 'admin' && Request::segment(2) == 'fleet-complaints'
        or Request::segment(1) == 'admin' && Request::segment(2) == 'tickets'
        or Request::segment(1) == 'admin' && Request::segment(2) == 'tickets' && Request::segment(3) == 'create') {
        echo 'menu-item-open';
    }?>">
        <a href="#" class="nav-link ">
            <i class="nav-icon fa fa-book"></i>
            <p class="nav_p">
                Support
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'tickets') {
                echo 'nav-item-active';
            }?>">
                <a href="{{ route('tickets.index') }}" class="nav-link ">
                    <i class="far fa-circle nav-icon"></i>
                    <p class="nav_p">{{ $pin_help_file }}</p>
                </a>
            </li>
            <li class="nav-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'tickets') {
                echo 'nav-item-active';
            }?>">
                <a href="{{ route('tickets.index') }}" class="nav-link ">
                    <i class="far fa-circle nav-icon"></i>
                    <p class="nav_p">{{ $Department_Help }}</p>
                </a>
            </li>
            <li class="nav-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'tickets' && Request::segment(3) == 'create') {
                echo 'nav-item-active';
            }?>">
                <a href="{{ url('admin/pinVersionInfo') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p class="nav_p">{{ $pin_version_info }}</p>
                </a>
            </li>
        </ul>
    </li>
    @endif
@endif
    {{-- Users--}}
    @if(Auth::user()->role == 1 or Auth::user()->role == 2 or Auth::user()->role == 3)

    @if(Auth::user()->configuration == 1)

    <li class="nav-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'Configuration') {
        echo 'menu-item-active';
    }?> <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'users'
        or Request::segment(1) == 'admin' && Request::segment(2) == 'categories'
        or Request::segment(1) == 'admin' && Request::segment(2) == 'event-categories'
        or Request::segment(1) == 'admin' && Request::segment(2) == 'documents'
        or Request::segment(1) == 'admin' && Request::segment(2) == 'folders'
        or Request::segment(1) == 'admin' && Request::segment(2) == 'setting') {
        echo 'menu-item-open';
    }?>">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p class="nav_p">
                Configuration
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            @if(Auth::user()->role == 1)
            <li class="nav-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'users') {
                echo 'menu-item-active';
            }?>">
                <a href="{{ route('users.index') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p class="nav_p">Manage User</p>
                </a>
            </li>
            @endif
            <li class="nav-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'users') {
                echo 'menu-item-active';
            }?>">
                <a href="{{ route('links.index') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p class="nav_p">Manage Department Links</p>
                </a>
            </li>
            <li class="nav-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'categories'
                or Request::segment(1) == 'admin' && Request::segment(2) == 'event-categories' ) {
                echo 'menu-item-open';
            }?>">
                <a href="#" class="nav-link ">
                    <i class="far fa-circle nav-icon"></i>
                    <p class="nav_p">Manage Categories
                        <i class="right fas fa-angle-left"></i>
                    </p>

                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'event-categories') {
                        echo 'menu-item-active';
                    }?>">
                        <a href="{{ route('event-categories.index') }}" class="nav-link ">
                            <i class="far fa-circle nav-icon"></i>
                            <p class="nav_p">Manage Event Category</p>
                        </a>
                    </li>
                    <li class="nav-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'categories') {
                        echo 'menu-item-active';
                    }?>">
                        <a href="{{ route('categories.index') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p class="nav_p">Manage Article Category</p>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'documents' or Request::segment(2) == 'folders') {
                echo 'menu-item-active';
            }?>">
                <a href="{{ route('documents.index') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p class="nav_p">Manage Documents</p>
                </a>
            </li>
            <li class="nav-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'setting') {
                echo 'menu-item-active';
            }?>">
                <a href="{{ route('setting.index') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p class="nav_p">Settings</p>
                </a>
            </li>
            @endif
            @endif
            
        </ul>
    </li>
</ul>
</nav>