import type { FileUploadList } from "~/types";
import type { Route } from "./+types/home";
import { UploadButton } from "~/components/upload-button";
import { Card } from "~/components/card";
import { File } from "~/components/file";
import { EmptyState } from "~/components/empty-state";

export function meta({}: Route.MetaArgs) {
  return [{ title: "AnchorLess visa dossier" }];
}

export async function clientLoader() {
  const res = await fetch(`http://localhost:8000/api/file-uploads`);
  const uploads: FileUploadList = await res.json();
  return uploads;
}

export async function clientAction({ request }: Route.ClientActionArgs) {
  if (request.method === "DELETE") {
    const { id } = await request.json();
    await fetch(`http://localhost:8000/api/file-uploads/${id}`, {
      method: "DELETE",
    });
    return null;
  }

  try {
    const data = await request.formData();
    const res = await fetch("http://localhost:8000/api/file-uploads", {
      method: "POST",
      body: data,
    });

    if (!res.ok) {
      return { error: "Upload failed. Please try again." };
    }

    return {};
  } catch {
    return { error: "Server unreachable. Please check your connection." };
  }
}

export default function Home({ loaderData }: Route.ComponentProps) {
  const { passport = [], photo = [], visa_request_form = [] } = loaderData;

  return (
    <main className="flex items-stretch justify-center pt-16 pb-4 gap-4">
      <Card>
        <p className="text-sm font-semibold text-slate-900 dark:text-white">
          National visa request form
        </p>
        {visa_request_form.length === 0 ? (
          <EmptyState />
        ) : (
          visa_request_form.map((file) => <File key={file.id} file={file} />)
        )}
        <UploadButton type="visa_request_form" />
      </Card>
      <Card>
        <p className="text-sm font-semibold text-slate-900 dark:text-white">
          Photos
        </p>
        {photo.length === 0 ? (
          <EmptyState />
        ) : (
          photo.map((file) => <File key={file.id} file={file} />)
        )}
        <UploadButton type="photo" />
      </Card>
      <Card>
        <p className="text-sm font-semibold text-slate-900 dark:text-white">
          Passport
        </p>
        {passport.length === 0 ? (
          <EmptyState />
        ) : (
          passport.map((file) => <File key={file.id} file={file} />)
        )}
        <UploadButton type="passport" />
      </Card>
    </main>
  );
}
