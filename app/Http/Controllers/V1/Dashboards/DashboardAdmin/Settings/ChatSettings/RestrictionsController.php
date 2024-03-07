<?php
namespace App\Http\Controllers\V1\Dashboards\DashboardAdmin\Settings\ChatSettings;

use App\Models\BadWord;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

/**
 * Restrictions chats Controller
 */
class RestrictionsController extends Controller
{
    /**
     * list Restricted Words
     * @param \Illuminate\Http\Request $request
     * @return jsonResponse
     */
    public function listRestrictedWords(Request $request)
    {
        $per_page = $request->per_page ?? 10;
        $badWords = BadWord::paginate($per_page);
        return finalResponse('success',200,$badWords);
    }



    /**
     * list active Restricted Words
     * @param \Illuminate\Http\Request $request
     * @return jsonResponse
     */
    public function listActiveRestrictedWords(Request $request)
    {
        $per_page = $request->per_page ?? 10;
        $badWords = BadWord::active()->paginate($per_page);
        return finalResponse('success',200,$badWords);
    }


    /**
     * list inactive Restricted Words
     * @param \Illuminate\Http\Request $request
     * @return jsonResponse
     */
    public function listInActiveRestrictedWords(Request $request)
    {
        $per_page = $request->per_page ?? 10;
        $badWords = BadWord::inactive()->paginate($per_page);
        return finalResponse('success',200,$badWords);
    }





    /**
     * add Restricted Word
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function addRestrictedWords(Request $request)
    {
        $request->validate([
            'word' => 'required|string',
        ]);
        $word = $request->word;
        $badWord = BadWord::create([
            'word' => $word,
        ]);
        return finalResponse('success',200, 'bad word added successfully');

    }


    /**
     * update Restricted Word
     * @param \Illuminate\Http\Request $request
     * @return jsonResponse
     */
    public function updateRestrictedWords(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'is_active' => 'required|boolean',
        ]);
        $id = $request->id;
        $badWord = BadWord::findOrFail($id);
        $badWord->update([
            'word' => $request->word,
            'is_active' => !$badWord->is_active,
        ]);
        return finalResponse('success', 200, 'bad word updated successfully');

    }

    /**
     * delete Restricted Words
     * @param \Illuminate\Http\Request $request
     * @return jsonResponse
     */
    public function deleteRestrictedWords(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);
        $id = $request->id;
        $badWord = BadWord::findOrFail($id);
        $badWord->delete();
        return finalResponse('success', 200, 'bad word deleted successfully');
    }



}

