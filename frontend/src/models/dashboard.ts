import type { Product } from "./product";
import type { Sale } from "./transactions";

export type DashboardData = {
  vendas_hoje: number;
  lucro_hoje: number;
  total_gasto: number;
  itens_vendidos: number;
  produtos: Product[];
  vendas: Sale[];
  compras: {
    fornecedor: string;
    custo_total: number;
  }[];
};
