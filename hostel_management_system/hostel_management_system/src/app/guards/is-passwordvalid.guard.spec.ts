import { TestBed } from '@angular/core/testing';

import { IsPasswordvalidGuard } from './is-passwordvalid.guard';

describe('IsPasswordvalidGuard', () => {
  let guard: IsPasswordvalidGuard;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    guard = TestBed.inject(IsPasswordvalidGuard);
  });

  it('should be created', () => {
    expect(guard).toBeTruthy();
  });
});
