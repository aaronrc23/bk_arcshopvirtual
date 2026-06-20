<?php

namespace App\services\Shop;

use App\Models\Administracion\Empresa;
use Illuminate\Support\Facades\DB;

class FooterService
{
    /**
     * Obtener la configuración del footer (primera empresa)
     */
    public function getFooter()
    {
        $empresa = Empresa::first();

        if (!$empresa) {
            return [
                'razon_social' => 'Distribuciones VLA',
                'nombre_comercial' => 'Distribuciones VLA',
                'descripcion' => 'Tu tienda de confianza con los mejores productos al mejor precio.',
                'direccion' => 'Av. Principal 123',
                'email' => 'contacto@vla.com',
                'telefono' => '999999999',
                'copyright_text' => '© ' . date('Y') . ' Distribuciones VLA E.I.R.L. Todos los derechos reservados.',
                'footer_links' => [
                    'tienda' => [
                        ['label' => 'Todos los productos', 'href' => '/catalogo'],
                        ['label' => 'Ofertas del día', 'href' => '#'],
                        ['label' => 'Nuevos ingresos', 'href' => '#'],
                        ['label' => 'Más vendidos', 'href' => '#'],
                        ['label' => 'Categorías', 'href' => '/catalogo'],
                    ],
                    'ayuda' => [
                        ['label' => 'Cómo comprar', 'href' => '#'],
                        ['label' => 'Seguimiento de pedido', 'href' => '#'],
                        ['label' => 'Devoluciones', 'href' => '#'],
                        ['label' => 'Preguntas frecuentes', 'href' => '#'],
                        ['label' => 'Términos y condiciones', 'href' => '#'],
                    ],
                ],
            ];
        }

        return $empresa;
    }

    /**
     * Actualizar datos del footer
     */
    public function updateFooter(int $id, array $data): array
    {
        return DB::transaction(function () use ($id, $data) {
            $empresa = Empresa::findOrFail($id);

            $empresa->update($data);

            return [
                'success' => true,
                'message' => 'Actualización exitosa',
                'detail' => 'Footer actualizado con éxito',
            ];
        });
    }

    /**
     * Actualizar solo los links del footer
     */
    public function updateFooterLinks(int $id, array $data): array
    {
        return DB::transaction(function () use ($id, $data) {
            $empresa = Empresa::findOrFail($id);

            $empresa->update([
                'footer_links' => $data['footer_links'],
            ]);

            return [
                'success' => true,
                'message' => 'Actualización exitosa',
                'detail' => 'Links del footer actualizados con éxito',
            ];
        });
    }
}
