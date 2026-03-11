export type FileUploadType = "visa_request_form" | "photo" | "passport";

export type FileUpload = {
  id: number;
  type: FileUploadType;
  originalName: string;
  mimeType: string;
  formattedSize: string;
  createdAt: string;
};

export type FileUploadList = Record<FileUploadType, FileUpload[]>;
