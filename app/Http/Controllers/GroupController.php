namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::withCount('members')->get();
        return view('grupos', compact('groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Group::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('grupos.index');
    }

    public function join(Group $group)
    {
        if (!$group->isMember(auth()->id())) {
            $group->members()->attach(auth()->id());
        }

        return redirect()->route('grupos.show', $group);
    }

    public function leave(Group $group)
    {
        if ($group->isMember(auth()->id())) {
            $group->members()->detach(auth()->id());
        }

        return redirect()->route('grupos.index');
    }

    public function show(Group $group)
    {
        $members = $group->members()->get();
        $posts = $group->posts()->with('user')->latest()->get();
        return view('grupo-detalhes', compact('group', 'members', 'posts'));
    }
}
