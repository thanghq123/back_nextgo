<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\ProductRequest;
use App\Models\Tenant\Attribute;
use App\Models\Tenant\AttributeValue;
use App\Models\Tenant\Product;
use App\Models\Tenant\Variation;
use App\Models\Tenant\VariationAttribute;

class ProductController extends Controller
{
    public function __construct(
        private Product $productModel,
        private Attribute $attributeModel,
        private AttributeValue $attributeValueModel,
        private Variation $variationModel,
        private VariationAttribute $variationAttributeModel,
        private ProductRequest $request
    ) {
    }

    public function list()
    {
        try {
            return responseApi($this->productModel::with(['attributes.attributeValues', 'variations'])
                ->select('products.*')
                ->selectRaw('(SELECT name FROM brands
                                                   WHERE id = products.brand_id)
                                                   as brand_name')
                ->selectRaw('(SELECT name FROM warranties
                                                   WHERE id = products.warranty_id)
                                                   as warranty_name')
                ->selectRaw('(SELECT name FROM item_units
                                                   WHERE id = products.item_unit_id)
                                                   as item_unit_name')
                ->selectRaw('(SELECT name FROM categories
                                                   WHERE id = products.category_id)
                                                   as category_name')
                ->selectRaw('DATE_FORMAT(created_at, "%d/%m/%Y %H:%i") as format_created_at')
                ->selectRaw('DATE_FORMAT(updated_at, "%d/%m/%Y %H:%i") as format_updated_at')
                ->orderBy('id', 'desc')
                ->paginate(10), true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function store()
    {
        try {
            $arrayAttributeValues = [];
            $arrayVariations = [];

            $product = $this->productModel::create([
                'name' => $this->request->name,
                'image' => $this->request->image,
                'weight' => $this->request->weight,
                'description' => $this->request->description,
                'manage_type' => $this->request->manage_type,
                'brand_id' => $this->request->brand_id == 0 && null,
                'warranty_id' => $this->request->warranty_id == 0 && null,
                'item_unit_id' => $this->request->item_unit_id == 0 && null,
                'category_id' => $this->request->category_id == 0 && null,
                'status' => $this->request->status,
            ]);

            if ($this->request['attributes']) {
                foreach ($this->request['attributes'] as $dataAttribute) {
                    $attribute = $this->attributeModel::create([
                        'product_id' => $product->id,
                        'name' => $dataAttribute['name']
                    ]);

                    foreach ($dataAttribute['attribute_values'] as $dataAttributeValue) {
                        $attributeValue = $this->attributeValueModel::create([
                            'attribute_id' => $attribute->id,
                            'value' => $dataAttributeValue['value']
                        ]);
                        array_push($arrayAttributeValues, $attributeValue->id);
                    }
                }
            }

            if ($this->request['variations']) {
                foreach ($this->request['variations'] as $dataVariation) {
                    $variation = $this->variationModel::create([
                        "product_id" => $product->id,
                        "sku" => $dataVariation['sku'],
                        "barcode" => $dataVariation['barcode'],
                        "variation_name" => $dataVariation['variation_name'],
                        "display_name" => $dataVariation['display_name'],
                        "image" => $dataVariation['image'],
                        "price_import" => $dataVariation['price_import'],
                        "price_export" => $dataVariation['price_export'],
                        "status" => $dataVariation['status']
                    ]);

                    array_push($arrayVariations, $variation->id);
                }

                if ($arrayAttributeValues) {
                    foreach ($arrayVariations as $keyVariation => $valueVariation) {
                        foreach ($arrayAttributeValues as $keyAttributeValue => $valueAttributeValue) {
                            $this->variationAttributeModel::create([
                                'variation_id' => $valueVariation,
                                'attribute_value_id' => $valueAttributeValue
                            ]);
                        }
                    }
                }
            }

            return responseApi("Tạo thành công!", true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function show()
    {
        try {
            return responseApi($this->productModel::with(['attributes.attributeValues', 'variations'])
                ->select('products.*')
                ->selectRaw('(SELECT name FROM brands
                                                   WHERE id = products.brand_id)
                                                   as brand_name')
                ->selectRaw('(SELECT name FROM warranties
                                                   WHERE id = products.warranty_id)
                                                   as warranty_name')
                ->selectRaw('(SELECT name FROM item_units
                                                   WHERE id = products.item_unit_id)
                                                   as item_unit_name')
                ->selectRaw('(SELECT name FROM categories
                                                   WHERE id = products.category_id)
                                                   as category_name')
                ->selectRaw('DATE_FORMAT(created_at, "%d/%m/%Y %H:%i") as format_created_at')
                ->selectRaw('DATE_FORMAT(updated_at, "%d/%m/%Y %H:%i") as format_updated_at')
                ->where('id', $this->request->id)
                ->first(), true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function update()
    {
        try {
            $arrayAttributeValues = [];
            $arrayVariations = [];

            $this->productModel::find($this->request->id)->update([
                'name' => $this->request->name,
                'image' => $this->request->image,
                'weight' => $this->request->weight,
                'description' => $this->request->description,
                'manage_type' => $this->request->manage_type,
                'brand_id' => $this->request->brand_id == 0 && null,
                'warranty_id' => $this->request->warranty_id == 0 && null,
                'item_unit_id' => $this->request->item_unit_id == 0 && null,
                'category_id' => $this->request->category_id == 0 && null,
                'status' => $this->request->status,
            ]);

            if ($this->request->status_attributes == 1) {
                if ($this->request['attributes']) {
                    $arrayIdVariationAttribute = [];

                    $variationAttribute = $this->productModel::query()
                        ->select('variation_attributes.variation_id')
                        ->join('variations', 'products.id', '=', 'variations.product_id')
                        ->join('variation_attributes', 'variations.id', '=', 'variation_attributes.variation_id')
                        ->where('products.id', $this->request->id)
                        ->get();

                    foreach ($variationAttribute as $v) {
                        array_push($arrayIdVariationAttribute, $v->variation_id);
                    }

                    $this->variationAttributeModel::whereIn('variation_id', $arrayIdVariationAttribute)->delete();

                    $product = $this->productModel::find($this->request->id);

                    $product->variations()->each(function ($variation) {
                        $variation->attributeValues()->each(function ($variationAttribute) {
                            $this->variationAttributeModel::where('id', $variationAttribute->id)->delete();
                        });
                        $variation->delete();
                    });

                    foreach ($this->request['attributes'] as $dataAttribute) {
                        $this->attributeModel::find($dataAttribute['id'])->update(['name' => $dataAttribute['name']]);

                        foreach ($dataAttribute['attribute_values'] as $dataAttributeValue) {
                            isset($dataAttributeValue['id']) ?
                                $this->attributeValueModel::find($dataAttributeValue['id'])->update([
                                    'value' => $dataAttributeValue['value']
                                ]) :
                                $attributeValue = $this->attributeValueModel::create([
                                    'attribute_id' => $dataAttribute['id'],
                                    'value' => $dataAttributeValue['value']
                                ]);

                            $idArray = isset($attributeValue) ? $attributeValue->id : $dataAttributeValue['id'];
                            array_push($arrayAttributeValues, $idArray);
                        }
                    }

                    foreach ($this->request['variations'] as $dataVariation) {
                        $variation = $this->variationModel::create([
                            "product_id" => $this->request->id,
                            "sku" => $dataVariation['sku'],
                            "barcode" => $dataVariation['barcode'],
                            "variation_name" => $dataVariation['variation_name'],
                            "display_name" => $dataVariation['display_name'],
                            "image" => $dataVariation['image'],
                            "price_import" => $dataVariation['price_import'],
                            "price_export" => $dataVariation['price_export'],
                            "status" => $dataVariation['status']
                        ]);

                        array_push($arrayVariations, $variation->id);
                    }

                    foreach ($arrayVariations as $keyVariation => $valueVariation) {
                        foreach ($arrayAttributeValues as $keyAttributeValue => $valueAttributeValue) {
                            $findVariationAttributeValue = $this->variationAttributeModel::query()
                                ->where('variation_id', $valueVariation)
                                ->where('attribute_value_id', $valueAttributeValue)
                                ->first();

                            !isset($findVariationAttributeValue) && $this->variationAttributeModel::create([
                                'variation_id' => $valueVariation,
                                'attribute_value_id' => $valueAttributeValue
                            ]);
                        }
                    }
                }
            } else {
                if ($this->request['variations']) {
                    foreach ($this->request['variations'] as $dataVariation) {
                        $this->variationModel::find($dataVariation['id'])->update([
                            "sku" => $dataVariation['sku'],
                            "barcode" => $dataVariation['barcode'],
                            "variation_name" => $dataVariation['variation_name'],
                            "display_name" => $dataVariation['display_name'],
                            "image" => $dataVariation['image'],
                            "price_import" => $dataVariation['price_import'],
                            "price_export" => $dataVariation['price_export'],
                            "status" => $dataVariation['status']
                        ]);
                    }
                }
            }

            return responseApi("Cập nhật thành công!", true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function delete()
    {
        try {
            $arrayIdVariationAttribute = [];
            $variationAttribute = $this->productModel::query()
                ->select('variation_attributes.variation_id')
                ->join('variations', 'products.id', '=', 'variations.product_id')
                ->join('variation_attributes', 'variations.id', '=', 'variation_attributes.variation_id')
                ->where('products.id', $this->request->id)
                ->get();

            foreach ($variationAttribute as $v) {
                array_push($arrayIdVariationAttribute, $v->variation_id);
            }

            $this->variationAttributeModel::whereIn('variation_id', $arrayIdVariationAttribute)->delete();

            $product = $this->productModel::find($this->request->id);

            $product->attributes()->each(function ($attribute) {
                $attribute->attributeValues()->delete();
                $attribute->delete();
            });

            $product->variations()->each(function ($variation) {
                $variation->attributeValues()->each(function ($variationAttribute) {
                    $this->variationAttributeModel::where('id', $variationAttribute->id)->delete();
                });
                $variation->delete();
            });

            $product->delete();
            return responseApi("Xóa thành công!", true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }
}
