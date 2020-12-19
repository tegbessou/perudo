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
export function getPagination(url) {
  const [loading, setLoading] = useState(true);
  const [items, setItems] = useState([]);
  const load = useCallback(async () => {
    const response = await apiCall(url);
    const responseData = await response.json();
    if (response.ok) {
      setItems(responseData['hydra:member']);
    }
    setLoading(false);
  }, [url]);

  return {
    items,
    loading,
    load
  }
}

export function sendData(url, callback) {
  const [pending, setPending] = useState(false);
  const [item, setItem] = useState(null);
  const [errors, setErrors] = useState([]);
  const [hasError, setHasError] = useState(false);
  const post = useCallback(async (body = null) => {
    setPending(true);
    const response = await apiCall(url, 'POST', body);
    const responseData = await response.json();
    if (response.ok) {
      setItem(responseData);
      if (callback) {
        callback(responseData);
      }
    } else {
      setErrors(responseData['hydra:description']);
      setHasError(true);
    }
    setPending(false);
  }, [url]);

  return {
    item,
    pending,
    errors,
    hasError,
    post,
    setItem
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