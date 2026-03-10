export type Sale = {
  id: number;
  cliente: string;
  lucro: number;
  produtos?: ProductTransaction[];
  cancelada: string | null;
};

export type Purchase = {
  id: number;
  fornecedor: string;
  custo_total: number;
  produtos?: ProductTransaction[];
};

export type ProductTransaction = {
  id: number;
  nome?: string;
  quantidade: number;
  preco_unitario: number;
};
