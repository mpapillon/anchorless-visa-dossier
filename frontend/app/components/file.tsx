import { FileImage, FileText, X } from "lucide-react";
import { useFetcher } from "react-router";
import type { FileUpload } from "~/types";

type FileProps = {
  file: FileUpload;
};

export function File({ file }: FileProps) {
  const fetcher = useFetcher();
  const isImage = ["image/jpeg", "image/png"].includes(file.mimeType);

  const deleteFile = () => {
    fetcher.submit(
      { id: file.id },
      { method: "DELETE", encType: "application/json" },
    );
  };

  return (
    <div
      className={`group flex items-center justify-between p-3 rounded-lg transition-all duration-200
        bg-white border border-slate-200 hover:border-slate-300 hover:shadow-md
        dark:bg-slate-800 dark:border-slate-700 dark:hover:border-slate-600 dark:hover:shadow-lg dark:hover:shadow-slate-900`}
    >
      <a
          href={`http://localhost:8000/api/file-uploads/${file.id}/download`}
          className="flex items-center gap-3 min-w-0"
        >
        <div className="shrink-0">
          {isImage ? (
            <FileImage className="w-5 h-5 dark:text-white text-slate-900" />
          ) : (
            <FileText className="w-5 h-5 dark:text-white text-slate-900" />
          )}
        </div>
        <div className="min-w-0">
          <p
            className={`text-sm font-medium truncate dark:text-white text-slate-900`}
          >
            {file.originalName}
          </p>
          <p className={`text-xs text-slate-500`}>{file.formattedSize}</p>
        </div>
      </a>
      <button
        onClick={deleteFile}
        className={`shrink-0 ml-2 p-1 rounded transition-all duration-200 opacity-0 group-hover:opacity-100 cursor-pointer
          dark:text-slate-500 dark:hover:text-red-400 dark:hover:bg-red-950
          text-slate-400 hover:text-red-500 hover:bg-red-50`}
        disabled={fetcher.state !== "idle"}
        aria-label="Delete file"
      >
        <X className="w-4 h-4" />
      </button>
    </div>
  );
}
