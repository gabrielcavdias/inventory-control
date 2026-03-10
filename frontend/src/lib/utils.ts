import type { ClassValue } from "clsx";
import { clsx } from "clsx";
import { twMerge } from "tailwind-merge";

export function cn(...inputs: ClassValue[]) {
  return twMerge(clsx(inputs));
}

export function api_url(path: string) {
  // Em uma aplicação real faria sentido usar .env e atualizar conforme o ambiente
  return "http://localhost:8010/api" + path;
}

export function money(n: number) {
  return new Intl.NumberFormat("pt-BR", {
    style: "currency",
    currency: "BRL",
  }).format(n);
}

export function date(date: string) {
  return new Intl.DateTimeFormat("pt-BR").format(new Date(date));
}
