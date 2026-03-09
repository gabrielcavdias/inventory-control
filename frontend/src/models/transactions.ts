export type Sale = {
  cliente: string;
  lucro: number;
};

export type Purchase = {
  fornecedor: string;
  custo_total: number;
};

export type ProductTransaction = {
  id: number;
  quantidade: number;
  preco_unitario: number;
};
