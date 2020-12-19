import {useCallback, useState} from 'react';

export function get(url) {
  const [loading, setLoading] = useState(true);
  const [item, setItem] = useState(null);
  const [error, setError] = useState(null);
  const [hasError, setHasError] = useState(false);
  const load = useCallback(async () => {
    const response = await apiCall(url);
    const responseData = await response.json();
    if (response.ok) {
      setItem(responseData);
    } else {
      setError(responseData['hydra:description']);
      setHasError(true);
    }
    setLoading(false);
  }, [url]);

  return {
    item,
    loading,
    error,
    hasError,
    load
  }
}

async function apiCall(url, method = 'GET', body = null) {
  const settings = {
    method: method,
    headers: {
      'Accept': 'application/ld+json',
      'Content-Type': 'application/json',
    }
  };

  if (body) {
    settings.body = JSON.stringify(body);
  }

  return await fetch(url, settings);
}