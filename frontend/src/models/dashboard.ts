import type { Product } from "./product";
import type { Purchase, Sale } from "./transactions";

export type DashboardData = {
  vendas_hoje: number;
  lucro_hoje: number;
  total_gasto: number;
  itens_vendidos: number;
  produtos: Product[];
  vendas: Sale[];
  compras: Purchase[];
};
