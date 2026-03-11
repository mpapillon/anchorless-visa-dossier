import type React from "react";

export function Card({ children }: React.PropsWithChildren) {
  return (
    <div className="flex flex-col min-h-72 rounded-2xl border border-gray-200 p-3 dark:border-gray-700 space-y-4 w-80">
      {children}
    </div>
  );
}
