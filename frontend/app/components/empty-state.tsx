import { Upload } from "lucide-react";

export function EmptyState() {
  return (
    <div className="text-center py-8">
      <div
        className={`w-12 h-12 mx-auto mb-3 rounded-full flex items-center justify-center dark:bg-slate-800 bg-slate-100`}
      >
        <Upload className={`w-6 h-6 dark:text-slate-600 text-slate-400`} />
      </div>
      <p className={`text-sm dark:text-slate-400 text-slate-500`}>
        No file uploaded yet
      </p>
    </div>
  );
}
