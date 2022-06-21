<?php
namespace App\Http\Controllers;


use App\Models\Helper;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Models\MysqlServer;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ProjectController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $projects = Project::orderBy('created_at', 'desc')->take(10)->get();
        return response()->json($projects, 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function select($id)
    {
        $projects = Project::get();
        foreach ($projects as $project) {
            Project::where('id_project', $project->id_project)->update(['is_selected' => 0]);
        }

        $project = Project::where('id_project', $id)->update(['is_selected' => 1]);
        return response()->json($project, 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function get($id)
    {
        $project = Project::where('id_project', $id)->first();
        return response()->json($project, 200);
    }

    public function store(Request $request)
    {
        
        $project = Project::create([
            'name' => $request->name,
            'website' => $request->website,
            'country_id' => $request->country,
            'currency_id' => $request->currency,
            'timezone_id' => $request->timezone,
            'token' => Helper::randomToken(), 
        ]);

        $mysqlServers = MysqlServer::get();

        $DbConnection = [];
        foreach ($mysqlServers as $mysqlServer) {
            $servername = $mysqlServer->mysql_host_ip;
            $username = $mysqlServer->mysql_user;
            $password = $mysqlServer->mysql_password;
            $db = $mysqlServer->mysql_db_name;
            $port = $mysqlServer->mysql_port;
            $servername = "{$servername}:{$port}";

            $conn = new \mysqli( $servername, $username, $password, $db );
            $result = $conn->query("SELECT * FROM projects");

            $DbConnection[] = [
                'servername' => $mysqlServer->mysql_host_ip,
                'username' => $mysqlServer->mysql_user,
                'password' => $mysqlServer->mysql_password,
                'db' => $mysqlServer->mysql_db_name,
                'count' => $result->num_rows,
            ];
        }

        $DbConnection = collect($DbConnection)->sortBy('count')->toArray();
        $DbConnectionFirst = reset($DbConnection);
        if (isset($DbConnectionFirst)) {
            $servername = $DbConnectionFirst['servername'];
            $username = $DbConnectionFirst['username'];
            $password = $DbConnectionFirst['password'];
            $db = $DbConnectionFirst['db'];
            $port = $mysqlServer->mysql_port;
            $servername = "{$servername}:{$port}";

            $countryId = $request->country;
            $currencyId = $request->currency;
            $timezoneId = $request->timezone;

            $conn = new \mysqli( $servername, $username, $password, $db);

            $sql = $conn->query("INSERT INTO projects ( id_project , name, website, country_id, currency_id, timezone_id, token)
            VALUES ( '$project->id_project', '$request->name', '$request->website', '$countryId', '$currencyId', '$timezoneId', '$project->token' )");
        }

        return response()->json(['message' => 'success'], 200);
    }

    public function update(Request $request, $id)
    {
        $project = Project::where('id_project', $id)->first();
        $project->update($request->all());
        $project = Project::where('id_project', $id)->first();
        return response()->json($project, 200);
    }

}
