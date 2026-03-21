<?php

namespace App\Http\Controllers\Logistica;

use App\Http\Controllers\Controller;
use App\Http\Requests\Logistica\Productos\CreateProdRqt;
use App\Http\Requests\Logistica\Productos\UpdateImgProd;
use App\Http\Requests\Logistica\Productos\UpdateProdRqt;
use App\Http\Resources\Logistica\Productos\ListProd;
use App\Http\Resources\Logistica\Productos\ProductImgResource;
use App\Models\Logistica\Productos;
use App\Services\Logistica\Productos\CreateServiceProducto;
use App\Services\Logistica\Productos\ImgServiceProducto;
use App\Services\Logistica\Productos\ListProductoServices;
use App\Services\Logistica\Productos\UpdateServiceProducto;

class ProductoController extends Controller
{
    protected $createProducto;
    protected $updateProducto;
    protected $imgProducto;

    protected $listProducto;
    public function __construct(CreateServiceProducto $createProducto, UpdateServiceProducto $updateProducto, ImgServiceProducto $imgProducto, ListProductoServices $listProducto)
    {
        $this->createProducto = $createProducto;
        $this->updateProducto = $updateProducto;
        $this->imgProducto = $imgProducto;
        $this->listProducto = $listProducto;
    }
    public function listProductos()
    {
        return ListProd::collection(
            Productos::with(['categoria', 'unidad', 'tipoAfectacion', 'imagenes'])
                ->get()
        );
    }

    public function listProductosByNombre($nombre)
    {
        return ListProd::collection($this->listProducto->findAll($nombre));
    }


    /**
     * @response [
     *   {
     *     "id": 1,
     *     "url": "productimg/imagen1.webp",
     *     "orden": 1,
     *     "is_principal": true
     *   }
     * ]
     */
    public function listImages(Productos $product)
    {
        return ProductImgResource::collection(
            $product->imagenes()->orderBy('orden')->get()
        );
    }


    public function listImagesById(int $productId)
    {
        return ProductImgResource::collection(
            $this->imgProducto->listImagesById($productId)
        );
    }



    public function storeProductos(CreateProdRqt $request)
    {
        $this->createProducto->create(
            $request->validated(),
            $request->file('imagenes')
        );

        return response()->json([
            "success" => true,
            'message' => 'Registro exitoso',
            'detail' => 'Producto creado con éxito',
        ], 201);
    }
    public function updateProductos(UpdateProdRqt $request, int $id)
    {
        $this->updateProducto->updateProductData(
            $id,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Exito',
            'detail' => 'Producto actualizado con éxito',
        ]);
    }

    public function updateIsPrincipal(int $id)
    {
        $this->imgProducto->updateIsPrincipal($id);
        return response()->json([
            'success' => true,
            'message' => 'Imagen principal actualizada con éxito',
        ]);
    }

    public function updateImgProductos(UpdateImgProd $request, int $id)
    {
        $this->updateProducto->updateProductImages(
            $id,
            $request->validated()['imagenes'],
            $request->file('newFiles', [])
        );

        return response()->json([
            'success' => true,
            'message'=> 'Exito',
            'detail' => 'Producto actualizado con éxito',
        ]);
    }

    public function desactivarProducto(int $id)
    {
        return $this->updateProducto->desactivar($id);
    }

    public function reactivarProducto(int $id)
    {
        return $this->updateProducto->reactivar($id);
    }



    public function deleteImage(int $id)
    {
        $this->updateProducto->deleteImage($id);

        return response()->json([
            'message' => 'Imagen eliminada',
        ]);
    }

    public function destroyProductos(int $id)
    {
        $this->updateProducto->deleteProducto($id);

        return response()->json([
            'message' => 'Producto eliminado',
        ]);
    }
}
