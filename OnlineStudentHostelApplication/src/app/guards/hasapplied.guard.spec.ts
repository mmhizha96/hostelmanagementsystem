import { TestBed } from '@angular/core/testing';

import { HasappliedGuard } from './hasapplied.guard';

describe('HasappliedGuard', () => {
  let guard: HasappliedGuard;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    guard = TestBed.inject(HasappliedGuard);
  });

  it('should be created', () => {
    expect(guard).toBeTruthy();
  });
});
