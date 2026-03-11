import { useId } from "react";
import { Upload } from "lucide-react";
import { useFetcher } from "react-router";
import type { FileUploadType } from "~/types";
import type { clientAction } from "~/routes/home";

type UploadButtonProps = {
  type: FileUploadType;
};

export function UploadButton({ type }: UploadButtonProps) {
  const id = useId();
  const fetcher = useFetcher<typeof clientAction>();

  const isIdle = fetcher.state === "idle";
  const error = isIdle ? fetcher.data?.error : null;
  const success = isIdle && fetcher.data && !fetcher.data.error;

  const handleFileSelect = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (!file) return;

    const formData = new FormData();
    formData.append("type", type);
    formData.append("file", file);

    fetcher.submit(formData, {
      method: "POST",
      encType: "multipart/form-data",
    });
  };

  const isLoading = fetcher.state !== "idle";

  return (
    <div className="mt-auto">
      <input
        id={id}
        type="file"
        onChange={handleFileSelect}
        className="hidden"
        accept="image/png,image/jpeg,application/pdf"
        disabled={isLoading}
      />

      <label
        htmlFor={id}
        className={`w-full py-2 px-3 rounded-lg font-semibold transition-all duration-300 flex items-center justify-center gap-2 group cursor-pointer ${
          isLoading
            ? "bg-slate-100 dark:bg-slate-700 text-slate-400 dark:text-slate-400 cursor-not-allowed pointer-events-none"
            : "bg-slate-900 dark:bg-white text-white dark:text-slate-900 hover:bg-slate-800 dark:hover:bg-slate-100 active:scale-95"
        }`}
      >
        {isLoading ? (
          <>
            <div className="w-5 h-5 border-2 border-slate-300 border-t-slate-600 rounded-full animate-spin" />
            <span>Sending...</span>
          </>
        ) : (
          <>
            <Upload className="w-5 h-5 transition-transform group-hover:scale-110" />
            <span>Choose file</span>
          </>
        )}
      </label>
      {error ? (
        <p className="mt-1.5 text-xs text-center text-red-500">{error}</p>
      ) : success ? (
        <p className="mt-1.5 text-xs text-center text-green-500">File uploaded successfully</p>
      ) : (
        <p className="mt-1.5 text-xs text-center text-slate-400">
          PNG, JPG or PDF — max 4 MB
        </p>
      )}
    </div>
  );
}
