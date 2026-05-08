// https://docs.pivity.com/#tag/Products/operation/getProduct
export interface Product {
    id: string;
    productId: string;
    name: string;
    price: {
        raw: number;
        original: {
            amount: string;
            currency: string;
        };
    };
}
