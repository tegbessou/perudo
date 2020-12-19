import { useEffect, useState } from "react";
import fetchApi from "../api/FetchAPi";

export default function useFetch(
  url,
  options = { body: {}, method: "GET" },
) {
  const [data, setData] = useState({
    response: null,
    error: false,
    loading: true,
  });

  const serializedOptions = JSON.stringify(options);

  useEffect(() => {
    if (url === null) {
      return data;
    }

    setData({ ...data, error: null, loading: true });

    fetchApi(
      url,
      options,
      (response, error, loading) => {
        setData({ response, error, loading });
      },
    );

    return () => {};
  }, [url, serializedOptions]);

  return data;
}
