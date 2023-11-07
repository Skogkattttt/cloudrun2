<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Cloud\Firestore\FirestoreClient;
use Google\Cloud\Core\GeoPoint;

class UserController extends Controller
{
    protected $firestore;

    public function __construct()
    {
        $firestoreConfig = config('database.connections.firestore');
        
        $this->firestore = new FirestoreClient([
            'keyFilePath' => $firestoreConfig['key_file_path'],
            'projectId' => $firestoreConfig['project_id'],
        ]);
    }

    public function index()
    {
        $datas = [];
        $documents = $this->firestore->collection('users')->documents();
        foreach ($documents as $document) {
            if ($document->exists()) {
                $datas[] = $document->data();
            }
        }

        return response()->json($datas);
    }

    public function show(string $phone)
    {
        $document = $this->firestore->collection('users')->document($phone)->snapshot();

        if ($document->exists()) {
            return response()->json($document->data());
        }

        return response()->json(['error' => 'Document not found'], 404);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        if (isset($data['gender'])) {
            $data['gender'] = $this->convertToBoolean($data['gender']);
        }
        if (isset($data['address']) && isset($data['address']['latitude']) && isset($data['address']['longitude'])) {
            $data['address'] = new GeoPoint($data['address']['latitude'], $data['address']['longitude']);
        }

        $documentRef = $this->firestore->collection('users')->newDocument();
        $documentRef->set($data);

        return response()->json(['id' => $documentRef->id()]);
    }

    public function update(Request $request, string $id)
    {
        $data = $request->all();
        if (isset($data['gender'])) {
            $data['gender'] = $this->convertToBoolean($data['gender']);
        }
        if (isset($data['address']) && isset($data['address']['latitude']) && isset($data['address']['longitude'])) {
            $data['address'] = new GeoPoint($data['address']['latitude'], $data['address']['longitude']);
        }

        $documentRef = $this->firestore->collection('users')->document($id);
        $documentRef->set($data, ['merge' => true]);

        return response()->json(['success' => true]);
    }

    public function destroy(string $id)
    {
        $documentRef = $this->firestore->collection('users')->document($id);
        $documentRef->delete();

        return response()->json(['success' => true]);
    }

    protected function convertToBoolean($value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_numeric($value)) {
            return (bool) $value;
        }

        if (is_string($value)) {
            return strtolower($value) === 'true' || $value === '1';
        }

        return false;
    }
}
