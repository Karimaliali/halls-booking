<?php

namespace App\Http\Controllers;

use App\Models\Hall;
use Illuminate\Http\Request;

class HallController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/halls",
     *     summary="Get all halls",
     *     operationId="getAllHalls",
     *     tags={"Halls"},
     *     @OA\Response(response=200, description="List of all halls")
     * )
     */
    public function index() {
        return Hall::all();
    }

    /**
     * @OA\Post(
     *     path="/api/halls",
     *     summary="Create a new hall",
     *     operationId="createHall",
     *     tags={"Halls"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","price","location","capacity"},
     *             @OA\Property(property="name", type="string", example="Grand Hall"),
     *             @OA\Property(property="price", type="number", example=5000),
     *             @OA\Property(property="location", type="string", example="Cairo"),
     *             @OA\Property(property="capacity", type="integer", example=200),
     *             @OA\Property(property="main_image", type="string", nullable=true, example="image.jpg")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Hall created successfully"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request) {
        $fields = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'main_image' => 'nullable|string',
            'location' =>'required|string',
            'capacity' =>'required|integer'
        ]);

        try {
        $hall = $request->user()->halls()->create($fields);

        return response([
            'status' => 'success',
            'message' => 'تمت إضافة القاعة بنجاح',
            'hall' => $hall
        ], 201);
            } catch (\Exception $e){
                return response(['error'=> $e->getMessage()],500);
            }
    }

    /**
     * @OA\Put(
     *     path="/api/halls/{id}",
     *     summary="Update a hall",
     *     operationId="updateHall",
     *     tags={"Halls"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Hall ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Grand Hall"),
     *             @OA\Property(property="price", type="number", example=5000),
     *             @OA\Property(property="location", type="string", example="Cairo"),
     *             @OA\Property(property="capacity", type="integer", example=200),
     *             @OA\Property(property="main_image", type="string", nullable=true, example="image.jpg")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Hall updated successfully"),
     *     @OA\Response(response=403, description="Unauthorized - you can only update your own halls"),
     *     @OA\Response(response=404, description="Hall not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(Request $request, $id) {
        try {
            $hall = Hall::findOrFail($id);

            // التحقق من أن المستخدم الحالي هو مالك القاعة
            if ($hall->user_id !== $request->user()->id) {
                return response([
                    'status' => 'error',
                    'message' => 'غير مصرح: يمكنك فقط تعديل قاعاتك الخاصة'
                ], 403);
            }

            $fields = $request->validate([
                'name' => 'sometimes|required|string',
                'price' => 'sometimes|required|numeric',
                'main_image' => 'nullable|string',
                'location' => 'sometimes|required|string',
                'capacity' => 'sometimes|required|integer'
            ]);

            $hall->update($fields);

            return response([
                'status' => 'success',
                'message' => 'تم تحديث القاعة بنجاح',
                'hall' => $hall
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response([
                'status' => 'error',
                'message' => 'القاعة غير موجودة'
            ], 404);
        } catch (\Exception $e) {
            return response(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/halls/{id}",
     *     summary="Delete a hall",
     *     operationId="deleteHall",
     *     tags={"Halls"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Hall ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Hall deleted successfully"),
     *     @OA\Response(response=403, description="Unauthorized - you can only delete your own halls"),
     *     @OA\Response(response=404, description="Hall not found")
     * )
     */
    public function destroy(Request $request, $id) {
        try {
            $hall = Hall::findOrFail($id);

            // التحقق من أن المستخدم الحالي هو مالك القاعة
            if ($hall->user_id !== $request->user()->id) {
                return response([
                    'status' => 'error',
                    'message' => 'غير مصرح: يمكنك فقط حذف قاعاتك الخاصة'
                ], 403);
            }

            $hall->delete();

            return response([
                'status' => 'success',
                'message' => 'تم حذف القاعة بنجاح'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response([
                'status' => 'error',
                'message' => 'القاعة غير موجودة'
            ], 404);
        } catch (\Exception $e) {
            return response(['error' => $e->getMessage()], 500);
        }
    }
}